function makeid(cpt)
{
	if (cpt == undefined || cpt == null || cpt== '') { cpt = 5; }
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=cpt; i-- ; )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

STR_PAD_RIGHT = 01; STR_PAD_LEFT = 02; STR_PAD_BOTH = 03;

function str_pad(input, pad_length, pad_string, pad_type) {
        //input = this;
        if(pad_type == undefined) pad_type = STR_PAD_RIGHT;
        if(pad_string == undefined) pad_string = ' ';
        switch(pad_type) {
                case STR_PAD_RIGHT:
                        if(input.length > pad_length) return input;
                        fillnum = pad_length - input.length;
                        fillstring = new Array(fillnum + 1).join(pad_string).substr(0, fillnum);
                        return input + fillstring;
                break;
                case STR_PAD_LEFT:
                        if(input.length > pad_length) return input;
                        fillnum = pad_length - input.length;
                        fillstring = new Array(fillnum + 1).join(pad_string).substr(0, fillnum);
                        return fillstring + input;
                break;
                case STR_PAD_BOTH:
                        if(input.length > pad_length) return input;
                        fillnum = pad_length - input.length;
                        fillnum_right = Math.ceil(fillnum / 2);
                        fillnum_left = Math.floor(fillnum / 2);
                        fillstring_left = new Array(fillnum_left + 1).join(pad_string).substr(0, fillnum_left);
                        fillstring_right = new Array(fillnum_right + 1).join(pad_string).substr(0, fillnum_right);
                        return fillstring_left + input + fillstring_right;
                break;
        }
}

// test si e est dans le tableau a
function in_array(a, e) {	for(var i in a) {	if (e == a[i]) return true; }	return false; }
// tableau, a-b (retire de a les elements de b)
function array_minus(a, b) { var t = new Array(); for(var i in a) if (!in_array(b, a[i])) t.push(a[i]); return t; }
// tableau, a-b (retire de a les elements de b) pour un tableau associatif
function array_minus_key(a, b) { var t = new Array(); for(var i in a) if (i!=b) t[i] = a[i]; return t; }