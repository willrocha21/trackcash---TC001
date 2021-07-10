function id( el ){
	return document.getElementById( el );
}
function mascarar(o) {
	if(o.type === 'tel'){
		mascara(o, telefone);
	}else if(o.value.length > 14){
		mascara(o, cnpj);
	}else{
		mascara(o, cpf);
	}
}
function mascara(o, f) {
	v_obj = o;
	v_fun = f;
	setTimeout("execmascara()", 1);
}
function execmascara() {
	v_obj.value = v_fun(v_obj.value);
}
function cpf(v) {
	v = v.replace( /\D/g , "");
	v = v.replace( /(\d{3})(\d)/ , "$1.$2");
	v = v.replace( /(\d{3})(\d)/ , "$1.$2");
	v = v.replace( /(\d{3})(\d{1,2})$/ , "$1-$2");
	return v;
}
function cnpj(v) {
	v = v.replace( /\D/g , "");
	v = v.replace( /^(\d{2})(\d)/ , "$1.$2");
	v = v.replace( /^(\d{2})\.(\d{3})(\d)/ , "$1.$2.$3");
	v = v.replace( /\.(\d{3})(\d)/ , ".$1/$2");
	v = v.replace( /(\d{4})(\d)/ , "$1-$2");
	return v;
}
function telefone(v) {
	v = v.replace( /\D/g , "");
	v = v.replace( /^(\d\d)(\d)/g , "($1) $2");
	v = v.replace( /(\d{4})(\d)/ , "$1-$2");
	return v;
}	