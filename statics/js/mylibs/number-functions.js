/*
 * Copyright (C) 2006 Baron Schwartz <baron at sequent dot org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

// @see http://www.xaprb.com/blog/2006/01/05/javascript-number-formatting/
 
// Abbreviations: LODP = Left Of Decimal Point, RODP = Right Of Decimal Point
Number.formatFunctions = {count:0};

// Constants useful for controlling the format of numbers in special cases.
Number.prototype.NaNstring   = 'NaN';
Number.prototype.posInfinity = 'Infinity';
Number.prototype.negInfinity = '-Infinity';
Number.prototype.conditionRE = /\[(>=|<=|=|>|<)([0-9\.]+)\]/;

Number.prototype.numberFormat = function(format, context) {
    if (isNaN(this) ) {
        return Number.prototype.NaNstring;
    }
    else if (this == +Infinity ) {
        return Number.prototype.posInfinity;
    }
    else if ( this == -Infinity) {
        return Number.prototype.negInfinity;
    }
    else if (Number.formatFunctions[format] == null) {
        Number.createNewFormat(format);
    }
    return this[Number.formatFunctions[format]](context);
};

Number.createNewFormat = function(format) {
    var funcName = "format" + Number.formatFunctions.count++;
    Number.formatFunctions[format] = funcName;
    var code = "Number.prototype." + funcName + " = function(context){\n";

    var formats = format.split(";");      // identify individual rules

    var equalConditionsCode = '';         // pull out equal conditions in front of the rest
    var otherConditionsCode = '';         // overlaping conditions (>= 1000 and <= 2000 etc. are handled left to right)
    var rulesWithoutConditionsCount = 0;  // up to 3 rules can have no conditions defined, they will be treated as [>0], [<0] and [=0] respectively
    var defaultRuleFormat = '###.##';     // use this if there are multiple rules but none or only one is without a custom condition

    if (formats.length < 2 && format.match(Number.prototype.conditionRE)==null) {
        code += Number.createTerminalFormat(format);
    } else {
        
        for (var i=0,format; format=formats[i]; i++){
            if ((result = format.match(Number.prototype.conditionRE))!==null) { // custom condition block
                var newFormat = format.replace(result[0],'');
                if (result[1]=='=') {
                    equalConditionsCode += Number.createConditionCode(result[1], result[2], newFormat);
                } else {
                    otherConditionsCode += Number.createConditionCode(result[1], result[2], newFormat);
                }
            } else { // this block has no custom condition
                switch (rulesWithoutConditionsCount) {
                    case 0: // positive condition
                        defaultRuleFormat = format; // see var defaultRuleFormat above
                        otherConditionsCode += Number.createConditionCode('>=', 0, format);
                        break;
                    case 1: // negative condition
                        otherConditionsCode += Number.createConditionCode('<', 0, format);
                        break;
                    case 2: // zero condition
                        equalConditionsCode += Number.createConditionCode('=', 0, format);
                        break;
                    default:
                        equalConditionsCode = "throw 'Too many semicolons in format string';" + equalConditionsCode;
                        break;
                }
                rulesWithoutConditionsCount++;
            }
        };

        if (rulesWithoutConditionsCount==1) { // we just have the default component, let's use it as a generic component instead of positive
            otherConditionsCode += Number.createConditionCode('<', 0, defaultRuleFormat);
        } else if (rulesWithoutConditionsCount==0) {  // no default component is present, use the default ###.## format for "all else"
            otherConditionsCode += Number.createConditionCode('all', 0, defaultRuleFormat);
        }

        code += equalConditionsCode + otherConditionsCode;

    }

    code += "}";

    eval(code);
};

Number.createConditionCode = function(condition, conditionNumber, format) {
    var conditionStr = '';
    
    if (condition == 'all') {
        return "\n"+"return this.numberFormat('" + String.escape(format)+ "', 1);";
    } else {
        switch(condition) {
            case '=':
                conditionStr = '==';
                break;
            case '>':
                conditionStr = '> ';
                break;
            case '<':
                conditionStr = '< ';
                break;
            case '>=':
                conditionStr = '>=';
                break;
            case '<=':
                conditionStr = '<=';
                break;
            default:
                throw 'Error! Unrecognized condition format!';
                break;
        }
    
        return "\n"+
            'if (this '+conditionStr+' '+parseFloat(conditionNumber,10)+') {'+
                "return this.numberFormat('" + String.escape(format)+ "', 1);"+
            '}';
    }
};


Number.createTerminalFormat = function(format) {
    // If there is no work to do, just return the literal value
    if (format.length > 0 && format.search(/[0#?]/) == -1) {
        return "return '" + String.escape(format) + "';\n";
    }
    // Negative values are always displayed without a minus sign when section separators are used.
    var code = "var val = (context == null) ? new Number(this) : Math.abs(this);\n";
    var thousands = false;
    var lodp = format;
    var rodp = "";
    var ldigits = 0;
    var rdigits = 0;
    var scidigits = 0;
    var scishowsign = false;
    var sciletter = "";
    // Look for (and remove) scientific notation instructions, which can be anywhere
    m = format.match(/\..*(e)([+-]?)(0+)/i);
    if (m) {
        sciletter = m[1];
        scishowsign = (m[2] == "+");
        scidigits = m[3].length;
        format = format.replace(/(e)([+-]?)(0+)/i, "");
    }
    // Split around the decimal point
    var m = format.match(/^([^.]*)\.(.*)$/);
    if (m) {
        lodp = m[1].replace(/\./g, "");
        rodp = m[2].replace(/\./g, "");
    }
    // Look for %
    if (format.indexOf('%') >= 0) {
        code += "val *= 100;\n";
    }
    // Look for comma-scaling to the left of the decimal point
    m = lodp.match(/(,+)(?:$|[^0#?,])/);
    if (m) {
        code += "val /= " + Math.pow(1000, m[1].length) + "\n;";
    }
    // Look for comma-separators
    if (lodp.search(/[0#?],[0#?]/) >= 0) {
        thousands = true;
    }
    // Nuke any extraneous commas
    if ((m) || thousands) {
        lodp = lodp.replace(/,/g, "");
    }
    // Figure out how many digits to the l/r of the decimal place
    m = lodp.match(/0[0#?]*/);
    if (m) {
        ldigits = m[0].length;
    }
    m = rodp.match(/[0#?]*/);
    if (m) {
        rdigits = m[0].length;
    }
    // Scientific notation takes precedence over rounding etc
    if (scidigits > 0) {
        code += "var sci = Number.toScientific(val,"
            + ldigits + ", " + rdigits + ", " + scidigits + ", " + scishowsign + ");\n"
            + "var arr = [sci.l, sci.r];\n";
    }
    else {
        // If there is no decimal point, round to nearest integer
        // rounding half AWAY from zero, as in Excel (as opposed to Math.round())
        // Hint: if context is present, we're always working with absolute values, so no adjustments are necessary
        if (format.indexOf('.') < 0) {
            code += "var sign = (this < 0 && context == null) ? -1 : 1;\n";
            code += "val = Math.round(val*sign)*sign;\n";
        }
        // Numbers are rounded to the correct number of digits to the right of the decimal
        code += "var arr = val.round(" + rdigits + ").toFixed(" + rdigits + ").split('.');\n";
        // There are at least "ldigits" digits to the left of the decimal, so add zeros if needed.
        code += "arr[0] = (val < 0 ? '-' : '') + String.leftPad((val < 0 ? arr[0].substring(1) : arr[0]), "
            + ldigits + ", '0');\n";
    }
    // Add thousands separators
    if (thousands) {
        code += "arr[0] = Number.addSeparators(arr[0]);\n";
    }
    // Insert the digits into the formatting string.  On the LHS, extra digits are copied
    // into the result.  On the RHS, rounding has chopped them off.
    code += "arr[0] = Number.injectIntoFormat(arr[0].reverse(), '"
        + String.escape(lodp.reverse()) + "', true).reverse();\n";
    if (rdigits > 0) {
        code += "arr[1] = Number.injectIntoFormat(arr[1], '" + String.escape(rodp) + "', false);\n";
    }
    if (scidigits > 0) {
        code += "arr[1] = arr[1].replace(/(\\d{" + rdigits + "})/, '$1" + sciletter + "' + sci.s);\n";
    }
    return code + "return arr.join('.');\n";
};

Number.toScientific = function(val, ldigits, rdigits, scidigits, showsign) {
    var result = {l:"", r:"", s:""};
    var ex = "";
    // Make ldigits + rdigits significant figures
    var before = Math.abs(val).toFixed(ldigits + rdigits + 1).trim('0');
    // Move the decimal point to the right of all digits we want to keep,
    // and round the resulting value off
    var after = Math.round(new Number(before.replace(".", "").replace(
        new RegExp("(\\d{" + (ldigits + rdigits) + "})(.*)"), "$1.$2"))).toFixed(0);
    // Place the decimal point in the new string
    if (after.length >= ldigits) {
        after = after.substring(0, ldigits) + "." + after.substring(ldigits);
    }
    else {
        after += '.';
    }
    // Find how much the decimal point moved.  This is #places to LODP in the original
    // number, minus the #places in the new number.  There are no left-padded zeroes in
    // the new number, so the calculation for it is simpler than for the old number.
    result.s = (before.indexOf(".") - before.search(/[1-9]/)) - after.indexOf(".");
    // The exponent is off by 1 when it gets moved to the left.
    if (result.s < 0) {
        result.s++;
    }
    // Split the value around the decimal point and pad the parts appropriately.
    result.l = (val < 0 ? '-' : '') + String.leftPad(after.substring(0, after.indexOf(".")), ldigits, "0");
    result.r = after.substring(after.indexOf(".") + 1);
    if (result.s < 0) {
        ex = "-";
    }
    else if (showsign) {
        ex = "+";
    }
    result.s = ex + String.leftPad(Math.abs(result.s).toFixed(0), scidigits, "0");
    return result;
};

Number.prototype.round = function(decimals) {
    if (decimals > 0) {
        // decimalPrecision variable is an ugly hack to limit toFixed() parameter to 20, which is ECMAScript maximum supported range
        // numbers like 0.54999999999999999, with #.# format, will still be rounded to 0.6, since there is simply not enough precision
        var decimalPrecision = Math.min(decimals + 10,20);
        var m = this.toFixed(decimalPrecision).match(
            new RegExp("(-?\\d*)\.(\\d{" + decimals + "})(\\d)\\d*$"));
        if (m && m.length) {
            var carryover = (m[1].charAt(0) == '-') ? -1 : 1; // negative number carryovers need to be negative, too
            var s = String.leftPad(Math.round(m[2] + "." + m[3]), decimals, "0");
            return (s.length > decimals) ? new Number(m[1]) + carryover : new Number(m[1] + "." + s);
        }
    }
    return this;
};

Number.injectIntoFormat = function(val, format, stuffExtras) {
    var i = 0;
    var j = 0;
    var result = "";
    var revneg = val.charAt(val.length - 1) == '-';
    if ( revneg ) {
       val = val.substring(0, val.length - 1);
    }
    while (i < format.length && j < val.length && format.substring(i).search(/[0#?]/) >= 0) {
        if (format.charAt(i).match(/[0#?]/)) {
            // It's a formatting character; copy the corresponding character
            // in the value to the result
            if (val.charAt(j) != '-') {
                result += val.charAt(j);
            }
            else {
                result += "0";
            }
            j++;
        }
        else {
            result += format.charAt(i);
        }
        ++i;
    }
    if ( revneg && j == val.length ) {
        result += '-';
    }
    if (j < val.length) {
        if (stuffExtras) {
            result += val.substring(j);
        }
        if ( revneg ) {
             result += '-';
        }
    }
    if (i < format.length) {
        result += format.substring(i);
    }
    return result.replace(/#/g, "").replace(/\?/g, " ");
};

Number.addSeparators = function(val) {
    return val.reverse().replace(/(\d{3})/g, "$1,").reverse().replace(/^(-)?,/, "$1");
};

String.prototype.reverse = function() {
    var res = "";
    for (var i = this.length; i > 0; --i) {
        res += this.charAt(i - 1);
    }
    return res;
};

String.prototype.trim = function(ch) {
    if (!ch) ch = ' ';
    return this.replace(new RegExp("^" + ch + "+|" + ch + "+$", "g"), "");
};

String.leftPad = function (val, size, ch) {
    var result = new String(val);
    if (ch == null) {
        ch = " ";
    }
    while (result.length < size) {
        result = ch + result;
    }
    return result;
};

String.escape = function(string) {
    return string.replace(/(')/g, "\\$1").replace(/[\r\n]/g, '');
};
