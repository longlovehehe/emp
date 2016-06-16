//限制文本域长度
function checktextare() {
var regC = /[^ -~]+/g;
var regE = /\D+/g;
var str = t1.value;

if (regC.test(str)){
    t1.value = t1.value.substr(0,10);
}

if(regE.test(str)){
    t1.value = t1.value.substr(0,20);
}
} 