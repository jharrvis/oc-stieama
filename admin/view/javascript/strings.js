// JavaScript Document

function string_to_url(string, blFolders) {
	//return a string which online contains lowercase, alpha-numeric, hyphenated characters.
	//define default
	if(blFolders==undefined) blFolders = false;//don't allow '/' sub folders by default
	//trim whitespace
	string = trim(string);
	//remove brackets
	string = string.replace("(","");
	string = string.replace(")","");
	//convert to lowercase
	string = string.toLowerCase();
	
	//filter alpha numeric characters
	var pattern;
	if(blFolders)	pattern=/[a-zA-Z0-9\. -\/]*/g;
	else			pattern=/[a-zA-Z0-9\. -]*/g;
	matches = string.match(pattern);
	if (matches!=null) {
		string = '';
		for(i=0; i<matches.length; i++) {
			string = string + matches[i];
		}
	}
	
	//convert white space to hyphens
	var pattern=/\s/g;
	string = string.replace(pattern,'-');
	
	//replace single/multiple hyphens with a single hyphen
	var pattern=/-+/g;
	string = string.replace(pattern,'-');
	
	return string;
}

function trim(str) {
	//trim white space from ends of string
	str = str.replace(/^\s+/, '');
	for (var i = str.length - 1; i >= 0; i--) {
		if (/\S/.test(str.charAt(i))) {
			str = str.substring(0, i + 1);
			break;
		}
	}
	return str;
}
