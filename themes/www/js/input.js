// JavaScript Document
$(function(){
/* Hide form input values on focus*/
$('input:text').each(function(){
var txtval = $(this).val();
$(this).focus(function(){
if($(this).val() == txtval){
$(this).val('')
}
});
$(this).blur(function(){
if($(this).val() == ""){
$(this).val(txtval);
}
});
});

$('textarea').each(function(){
var txtval = $(this).val();
$(this).focus(function(){
if($(this).val() == txtval){
$(this).val('')
}
});
$(this).blur(function(){
if($(this).val() == ""){
$(this).val(txtval);
}
});
});
});