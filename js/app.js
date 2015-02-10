// @codekit-prepend "../lib/foundation/js/foundation.js"
// @codekit-prepend "../lib/foundation/js/foundation.clearing.js"

(function($) {
	if (!Foundation.stylesheet) {
		Foundation._style_element = $('<style></style>').appendTo('head')[0];
		Foundation.stylesheet     = Foundation._style_element.styleSheet;

		if (Foundation.stylesheet) {
			Foundation.stylesheet.cssRules = {
				length: 0
			};

			Foundation.stylesheet.insertRule = function(rule, index) {
				var media, mediaMatch, mediaRegex, namespace, ruleMatch, ruleRegex;
				mediaRegex = /^\s*@media\s*(.*?)\s*\{\s*(.*?)\s*\}\s*$/;
				mediaMatch = mediaRegex.exec(rule);
				media      = '';

				if (mediaMatch) {
					media = '@media ' + mediaMatch[1] + ' ';
					rule  = mediaMatch[2];
				}

				ruleRegex = /^\s*(.*?)\s*\{\s*(.*?)\s*\}\s*$/;
				ruleMatch = ruleRegex.exec(rule);
				namespace = '' + media + ruleMatch[1];
				rule      = ruleMatch[2];

				return this.addRule(namespace, rule);
			};
		} else if (window.console) {
			console.log('Could not fix Foundation CSS rules...');
		}
	}
})(jQuery);

jQuery(document).foundation();