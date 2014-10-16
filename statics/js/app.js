(function($, window, document, undefined){
    //Loading
    $(document).ajaxStart(function(){
        $('#loading').fadeIn(300);
        $('#loading-overlay').delay(400).fadeOut(300 * 2);
    }).ajaxSuccess(function(){
        $('#loading').fadeOut(300);
    })
    //Date format
    Date.prototype.format = function(format){
        var o = {
            "M+" : this.getMonth()+1, //month
            "d+" : this.getDate(), //day
            "h+" : this.getHours(), //hour
            "m+" : this.getMinutes(), //minute
            "s+" : this.getSeconds(), //second
            "q+" : Math.floor((this.getMonth()+3)/3), //quarter
            "S" : this.getMilliseconds() //millisecond
        }

        if(/(y+)/.test(format)) {
            format = format.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
        }

        for(var k in o) {
            if(new RegExp("("+ k +")").test(format)) {
                format = format.replace(RegExp.$1, RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length));
            }
        }
        return format;
    }
})(jQuery, this, document);

var Grw = {
    init:function(){

    },
    /**
     * @param theme wood/todo/iphone/note
     *
     */
    alert:function(title,message,theme){
        if(typeof theme == 'undefined'){
            var theme = 'wood'
        }
        $.jGrowl(message,{header:title,theme:theme})
    }
}