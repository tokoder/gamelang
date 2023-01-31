<?php
/**
 * CodeIgniter Gamelang
 *
 * An open source codeigniter management system
 *
 * @package 	CodeIgniter Gamelang
 * @author		Tokoder Team
 * @copyright	Copyright (c) 2022, Tokoder (https://tokoder.com/)
 * @license 	https://opensource.org/licenses/MIT	MIT License
 * @link		https://github.com/tokoder/gamelang
 * @since		1.0.0
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Assets
 *
 * A minification driver system for CodeIgniter
 *
 * @package     ci-Assets
 * @author		Tokoder Team, who hacked at it a bit.
 * @author		Eric Barnes
 * @copyright   Copyright (c) Eric Barnes. (http://ericlbarnes.com/)
 * @license     http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * @link        http://ericlbarnes.com
 * @since       Version 1.0
 * @filesource
 */

/**
 * Assets CSS Driver
 *
 * @subpackage	Drivers
 */
class Assets_css extends CI_Driver
{

	/**
	 * Constructor
	 *
	 * @return \Assets_css
	 */
	public function __construct()
	{
		log_message('debug', 'Assets CSS Initialized');
	}

	// ------------------------------------------------------------------------

	/**
	 * Min
	 *
	 * Assets a CSS file
	 *
	 * @param string $file
	 * @param bool $compact
	 * @param null $is_aggregated
	 * @return string
	 */
	public function min($file, $compact = TRUE, $is_aggregated = NULL)
	{
		if ($is_aggregated) {
			$file = $this->remove_charsets($file);
		}

		if ($compact != FALSE) {
			return trim(CSSmin::optimize($file)) . "\n";
		} else {
			return "\n" . trim($file) . "\n\n";
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Remove charsets
	 *
	 * Charset declarations removal to support do combine function
	 * in order to set a new one user defined charset at the beggining of the document
	 * to keep standars compliance (and fix Webkit buggy behaviours)
	 *
	 * @param string $contents
	 * @return string
	 */
	private function remove_charsets($contents)
	{
		return preg_replace('/^\s*@charset\s+[\'"](?:\S*)\b[\'"];/i', '', $contents);
	}
}

// ------------------------------------------------------------------------

/*!
 * YUI Compressor
 * http://developer.yahoo.com/yui/compressor/
 * Author: Julien Lecomte - http://www.julienlecomte.net/
 * Copyright (c) 2013 Yahoo! Inc. All rights reserved.
 * The copyrights embodied in the content of this file are licensed
 * by Yahoo! Inc. under the BSD (revised) open source license.
 */

class CSSmin
{
    const QUERY_FRACTION = '_CSSMIN_QF_';
    const COMMENT_TOKEN = '_CSSMIN_CMT_%d_';
    const COMMENT_TOKEN_START = '_CSSMIN_CMT_';
    const RULE_BODY_TOKEN = '_CSSMIN_RBT_%d_';
    const PRESERVED_TOKEN = '_CSSMIN_PTK_%d_';

    // Token lists
    private $comments = array();
    private $ruleBodies = array();
    private $preservedTokens = array();

    // Output options
    private $keepImportantComments = true;
    private $keepSourceMapComment = false;
    private $linebreakPosition = 0;

    // PHP ini limits
    private $raisePhpLimits;
    private $memoryLimit;
    private $maxExecutionTime = 60; // 1 min
    private $pcreBacktrackLimit;
    private $pcreRecursionLimit;

    // Color maps
    private $hexToNamedColorsMap;
    private $namedToHexColorsMap;

    // Regexes
    private $numRegex;
    private $charsetRegex = '/@charset [^;]+;/Si';
    private $importRegex = '/@import [^;]+;/Si';
    private $namespaceRegex = '/@namespace [^;]+;/Si';
    private $namedToHexColorsRegex;
    private $shortenOneZeroesRegex;
    private $shortenTwoZeroesRegex;
    private $shortenThreeZeroesRegex;
    private $shortenFourZeroesRegex;
    private $unitsGroupRegex = '(?:ch|cm|em|ex|gd|in|mm|px|pt|pc|q|rem|vh|vmax|vmin|vw|%)';

	/**
	 * Minify Css.
	 *
	 * @param string $Css to be minified
	 *
	 * @return string
	 */
	public static function optimize($css)
	{
		$cssmin = new CSSmin();
		return $cssmin->run($css);
	}

    /**
     * @param bool|int $raisePhpLimits If true, PHP settings will be raised if needed
     */
    public function __construct($raisePhpLimits = true)
    {
        $this->raisePhpLimits = (bool) $raisePhpLimits;
        $this->memoryLimit = 128 * 1048576; // 128MB in bytes
        $this->pcreBacktrackLimit = 1000 * 1000;
        $this->pcreRecursionLimit = 500 * 1000;
        $this->hexToNamedColorsMap = Colors::getHexToNamedMap();
        $this->namedToHexColorsMap = Colors::getNamedToHexMap();
        $this->namedToHexColorsRegex = sprintf(
            '/([:,( ])(%s)( |,|\)|;|$)/Si',
            implode('|', array_keys($this->namedToHexColorsMap))
        );
        $this->numRegex = sprintf('-?\d*\.?\d+%s?', $this->unitsGroupRegex);
        $this->setShortenZeroValuesRegexes();
    }

    /**
     * Parses & minifies the given input CSS string
     * @param string $css
     * @return string
     */
    public function run($css = '')
    {
        if (empty($css) || !is_string($css)) {
            return '';
        }

        $this->resetRunProperties();

        if ($this->raisePhpLimits) {
            $this->doRaisePhpLimits();
        }

        return $this->minify($css);
    }

    /**
     * Sets whether to keep or remove sourcemap special comment.
     * Sourcemap comments are removed by default.
     * @param bool $keepSourceMapComment
     */
    public function keepSourceMapComment($keepSourceMapComment = true)
    {
        $this->keepSourceMapComment = (bool) $keepSourceMapComment;
    }

    /**
     * Sets whether to keep or remove important comments.
     * Important comments outside of a declaration block are kept by default.
     * @param bool $removeImportantComments
     */
    public function removeImportantComments($removeImportantComments = true)
    {
        $this->keepImportantComments = !(bool) $removeImportantComments;
    }

    /**
     * Sets the approximate column after which long lines will be splitted in the output
     * with a linebreak.
     * @param int $position
     */
    public function setLineBreakPosition($position)
    {
        $this->linebreakPosition = (int) $position;
    }

    /**
     * Sets the memory limit for this script
     * @param int|string $limit
     */
    public function setMemoryLimit($limit)
    {
        $this->memoryLimit = Utils::normalizeInt($limit);
    }

    /**
     * Sets the maximum execution time for this script
     * @param int|string $seconds
     */
    public function setMaxExecutionTime($seconds)
    {
        $this->maxExecutionTime = (int) $seconds;
    }

    /**
     * Sets the PCRE backtrack limit for this script
     * @param int $limit
     */
    public function setPcreBacktrackLimit($limit)
    {
        $this->pcreBacktrackLimit = (int) $limit;
    }

    /**
     * Sets the PCRE recursion limit for this script
     * @param int $limit
     */
    public function setPcreRecursionLimit($limit)
    {
        $this->pcreRecursionLimit = (int) $limit;
    }

    /**
     * Builds regular expressions needed for shortening zero values
     */
    private function setShortenZeroValuesRegexes()
    {
        $zeroRegex = '0'. $this->unitsGroupRegex;
        $numOrPosRegex = '('. $this->numRegex .'|top|left|bottom|right|center) ';
        $oneZeroSafeProperties = array(
            '(?:line-)?height',
            '(?:(?:min|max)-)?width',
            'top',
            'left',
            'background-position',
            'bottom',
            'right',
            'border(?:-(?:top|left|bottom|right))?(?:-width)?',
            'border-(?:(?:top|bottom)-(?:left|right)-)?radius',
            'column-(?:gap|width)',
            'margin(?:-(?:top|left|bottom|right))?',
            'outline-width',
            'padding(?:-(?:top|left|bottom|right))?'
        );

        // First zero regex
        $regex = '/(^|;)('. implode('|', $oneZeroSafeProperties) .'):%s/Si';
        $this->shortenOneZeroesRegex = sprintf($regex, $zeroRegex);

        // Multiple zeroes regexes
        $regex = '/(^|;)(margin|padding|border-(?:width|radius)|background-position):%s/Si';
        $this->shortenTwoZeroesRegex = sprintf($regex, $numOrPosRegex . $zeroRegex);
        $this->shortenThreeZeroesRegex = sprintf($regex, $numOrPosRegex . $numOrPosRegex . $zeroRegex);
        $this->shortenFourZeroesRegex = sprintf($regex, $numOrPosRegex . $numOrPosRegex . $numOrPosRegex . $zeroRegex);
    }

    /**
     * Resets properties whose value may change between runs
     */
    private function resetRunProperties()
    {
        $this->comments = array();
        $this->ruleBodies = array();
        $this->preservedTokens = array();
    }

    /**
     * Tries to configure PHP to use at least the suggested minimum settings
     * @return void
     */
    private function doRaisePhpLimits()
    {
        $phpLimits = array(
            'memory_limit' => $this->memoryLimit,
            'max_execution_time' => $this->maxExecutionTime,
            'pcre.backtrack_limit' => $this->pcreBacktrackLimit,
            'pcre.recursion_limit' =>  $this->pcreRecursionLimit
        );

        // If current settings are higher respect them.
        foreach ($phpLimits as $name => $suggested) {
            $current = Utils::normalizeInt(ini_get($name));

            if ($current >= $suggested) {
                continue;
            }

            // memoryLimit exception: allow -1 for "no memory limit".
            if ($name === 'memory_limit' && $current === -1) {
                continue;
            }

            // maxExecutionTime exception: allow 0 for "no memory limit".
            if ($name === 'max_execution_time' && $current === 0) {
                continue;
            }

            ini_set($name, $suggested);
        }
    }

    /**
     * Registers a preserved token
     * @param string $token
     * @return string The token ID string
     */
    private function registerPreservedToken($token)
    {
        $tokenId = sprintf(self::PRESERVED_TOKEN, count($this->preservedTokens));
        $this->preservedTokens[$tokenId] = $token;
        return $tokenId;
    }

    /**
     * Registers a candidate comment token
     * @param string $comment
     * @return string The comment token ID string
     */
    private function registerCommentToken($comment)
    {
        $tokenId = sprintf(self::COMMENT_TOKEN, count($this->comments));
        $this->comments[$tokenId] = $comment;
        return $tokenId;
    }

    /**
     * Registers a rule body token
     * @param string $body the minified rule body
     * @return string The rule body token ID string
     */
    private function registerRuleBodyToken($body)
    {
        if (empty($body)) {
            return '';
        }

        $tokenId = sprintf(self::RULE_BODY_TOKEN, count($this->ruleBodies));
        $this->ruleBodies[$tokenId] = $body;
        return $tokenId;
    }

    /**
     * Parses & minifies the given input CSS string
     * @param string $css
     * @return string
     */
    private function minify($css)
    {
        // Process data urls
        $css = $this->processDataUrls($css);

        // Process comments
        $css = preg_replace_callback(
            '/(?<!\\\\)\/\*(.*?)\*(?<!\\\\)\//Ss',
            array($this, 'processCommentsCallback'),
            $css
        );

        // IE7: Process Microsoft matrix filters (whitespaces between Matrix parameters). Can contain strings inside.
        $css = preg_replace_callback(
            '/filter:\s*progid:DXImageTransform\.Microsoft\.Matrix\(([^)]+)\)/Ss',
            array($this, 'processOldIeSpecificMatrixDefinitionCallback'),
            $css
        );

        // Process quoted unquotable attribute selectors to unquote them. Covers most common cases.
        // Likelyhood of a quoted attribute selector being a substring in a string: Very very low.
        $css = preg_replace(
            '/\[\s*([a-z][a-z-]+)\s*([\*\|\^\$~]?=)\s*[\'"](-?[a-z_][a-z0-9-_]+)[\'"]\s*\]/Ssi',
            '[$1$2$3]',
            $css
        );

        // Process strings so their content doesn't get accidentally minified
        $css = preg_replace_callback(
            '/(?:"(?:[^\\\\"]|\\\\.|\\\\)*")|'."(?:'(?:[^\\\\']|\\\\.|\\\\)*')/S",
            array($this, 'processStringsCallback'),
            $css
        );

        // Normalize all whitespace strings to single spaces. Easier to work with that way.
        $css = preg_replace('/\s+/S', ' ', $css);

        // Process import At-rules with unquoted URLs so URI reserved characters such as a semicolon may be used safely.
        $css = preg_replace_callback(
            '/@import url\(([^\'"]+?)\)( |;)/Si',
            array($this, 'processImportUnquotedUrlAtRulesCallback'),
            $css
        );

        // Process comments
        $css = $this->processComments($css);

        // Process rule bodies
        $css = $this->processRuleBodies($css);

        // Process at-rules and selectors
        $css = $this->processAtRulesAndSelectors($css);

        // Restore preserved rule bodies before splitting
        $css = strtr($css, $this->ruleBodies);

        // Split long lines in output if required
        $css = $this->processLongLineSplitting($css);

        // Restore preserved comments and strings
        $css = strtr($css, $this->preservedTokens);

        return trim($css);
    }

    /**
     * Searches & replaces all data urls with tokens before we start compressing,
     * to avoid performance issues running some of the subsequent regexes against large string chunks.
     * @param string $css
     * @return string
     */
    private function processDataUrls($css)
    {
        $ret = '';
        $searchOffset = $substrOffset = 0;

        // Since we need to account for non-base64 data urls, we need to handle
        // ' and ) being part of the data string.
        while (preg_match('/url\(\s*(["\']?)data:/Si', $css, $m, PREG_OFFSET_CAPTURE, $searchOffset)) {
            $matchStartIndex = $m[0][1];
            $dataStartIndex = $matchStartIndex + 4; // url( length
            $searchOffset = $matchStartIndex + strlen($m[0][0]);
            $terminator = $m[1][0]; // ', " or empty (not quoted)
            $terminatorRegex = '/(?<!\\\\)'. (strlen($terminator) === 0 ? '' : $terminator.'\s*') .'(\))/S';

            $ret .= substr($css, $substrOffset, $matchStartIndex - $substrOffset);

            // Terminator found
            if (preg_match($terminatorRegex, $css, $matches, PREG_OFFSET_CAPTURE, $searchOffset)) {
                $matchEndIndex = $matches[1][1];
                $searchOffset = $matchEndIndex + 1;
                $token = substr($css, $dataStartIndex, $matchEndIndex - $dataStartIndex);

                // Remove all spaces only for base64 encoded URLs.
                if (stripos($token, 'base64,') !== false) {
                    $token = preg_replace('/\s+/S', '', $token);
                }

                $ret .= 'url('. $this->registerPreservedToken(trim($token)) .')';
            // No end terminator found, re-add the whole match. Should we throw/warn here?
            } else {
                $ret .= substr($css, $matchStartIndex, $searchOffset - $matchStartIndex);
            }

            $substrOffset = $searchOffset;
        }

        $ret .= substr($css, $substrOffset);

        return $ret;
    }

    /**
     * Registers all comments found as candidates to be preserved.
     * @param array $matches
     * @return string
     */
    private function processCommentsCallback($matches)
    {
        return '/*'. $this->registerCommentToken($matches[1]) .'*/';
    }

    /**
     * Preserves old IE Matrix string definition
     * @param array $matches
     * @return string
     */
    private function processOldIeSpecificMatrixDefinitionCallback($matches)
    {
        return 'filter:progid:DXImageTransform.Microsoft.Matrix('. $this->registerPreservedToken($matches[1]) .')';
    }

    /**
     * Preserves strings found
     * @param array $matches
     * @return string
     */
    private function processStringsCallback($matches)
    {
        $match = $matches[0];
        $quote = substr($match, 0, 1);
        $match = substr($match, 1, -1);

        // maybe the string contains a comment-like substring?
        // one, maybe more? put'em back then
        if (strpos($match, self::COMMENT_TOKEN_START) !== false) {
            $match = strtr($match, $this->comments);
        }

        // minify alpha opacity in filter strings
        $match = str_ireplace('progid:DXImageTransform.Microsoft.Alpha(Opacity=', 'alpha(opacity=', $match);

        return $quote . $this->registerPreservedToken($match) . $quote;
    }

    /**
     * Searches & replaces all import at-rule unquoted urls with tokens so URI reserved characters such as a semicolon
     * may be used safely in a URL.
     * @param array $matches
     * @return string
     */
    private function processImportUnquotedUrlAtRulesCallback($matches)
    {
        return '@import url('. $this->registerPreservedToken($matches[1]) .')'. $matches[2];
    }

    /**
     * Preserves or removes comments found.
     * @param string $css
     * @return string
     */
    private function processComments($css)
    {
        foreach ($this->comments as $commentId => $comment) {
            $commentIdString = '/*'. $commentId .'*/';

            // ! in the first position of the comment means preserve
            // so push to the preserved tokens keeping the !
            if ($this->keepImportantComments && strpos($comment, '!') === 0) {
                $preservedTokenId = $this->registerPreservedToken($comment);
                // Put new lines before and after /*! important comments
                $css = str_replace($commentIdString, "\n/*$preservedTokenId*/\n", $css);
                continue;
            }

            // # sourceMappingURL= in the first position of the comment means sourcemap
            // so push to the preserved tokens if {$this->keepSourceMapComment} is truthy.
            if ($this->keepSourceMapComment && strpos($comment, '# sourceMappingURL=') === 0) {
                $preservedTokenId = $this->registerPreservedToken($comment);
                // Add new line before the sourcemap comment
                $css = str_replace($commentIdString, "\n/*$preservedTokenId*/", $css);
                continue;
            }

            // Keep empty comments after child selectors (IE7 hack)
            // e.g. html >/**/ body
            if (strlen($comment) === 0 && strpos($css, '>/*'.$commentId) !== false) {
                $css = str_replace($commentId, $this->registerPreservedToken(''), $css);
                continue;
            }

            // in all other cases kill the comment
            $css = str_replace($commentIdString, '', $css);
        }

        // Normalize whitespace again
        $css = preg_replace('/ +/S', ' ', $css);

        return $css;
    }

    /**
     * Finds, minifies & preserves all rule bodies.
     * @param string $css the whole stylesheet.
     * @return string
     */
    private function processRuleBodies($css)
    {
        $ret = '';
        $searchOffset = $substrOffset = 0;

        while (($blockStartPos = strpos($css, '{', $searchOffset)) !== false) {
            $blockEndPos = strpos($css, '}', $blockStartPos);
            $nextBlockStartPos = strpos($css, '{', $blockStartPos + 1);
            $ret .= substr($css, $substrOffset, $blockStartPos - $substrOffset);

            if ($nextBlockStartPos !== false && $nextBlockStartPos < $blockEndPos) {
                $ret .= substr($css, $blockStartPos, $nextBlockStartPos - $blockStartPos);
                $searchOffset = $nextBlockStartPos;
            } else {
                $ruleBody = substr($css, $blockStartPos + 1, $blockEndPos - $blockStartPos - 1);
                $ruleBodyToken = $this->registerRuleBodyToken($this->processRuleBody($ruleBody));
                $ret .= '{'. $ruleBodyToken .'}';
                $searchOffset = $blockEndPos + 1;
            }

            $substrOffset = $searchOffset;
        }

        $ret .= substr($css, $substrOffset);

        return $ret;
    }

    /**
     * Compresses non-group rule bodies.
     * @param string $body The rule body without curly braces
     * @return string
     */
    private function processRuleBody($body)
    {
        $body = trim($body);

        // Remove spaces before the things that should not have spaces before them.
        $body = preg_replace('/ ([:=,)*\/;\n])/S', '$1', $body);

        // Remove the spaces after the things that should not have spaces after them.
        $body = preg_replace('/([:=,(*\/!;\n]) /S', '$1', $body);

        // Replace multiple semi-colons in a row by a single one
        $body = preg_replace('/;;+/S', ';', $body);

        // Remove semicolon before closing brace except when:
        // - The last property is prefixed with a `*` (lte IE7 hack) to avoid issues on Symbian S60 3.x browsers.
        if (!preg_match('/\*[a-z0-9-]+:[^;]+;$/Si', $body)) {
            $body = rtrim($body, ';');
        }

        // Remove important comments inside a rule body (because they make no sense here).
        if (strpos($body, '/*') !== false) {
            $body = preg_replace('/\n?\/\*[A-Z0-9_]+\*\/\n?/S', '', $body);
        }

        // Empty rule body? Exit :)
        if (empty($body)) {
            return '';
        }

        // Shorten font-weight values
        $body = preg_replace(
            array('/(font-weight:)bold\b/Si', '/(font-weight:)normal\b/Si'),
            array('${1}700', '${1}400'),
            $body
        );

        // Shorten background property
        $body = preg_replace('/(background:)(?:none|transparent)( !|;|$)/Si', '${1}0 0$2', $body);

        // Shorten opacity IE filter
        $body = str_ireplace('progid:DXImageTransform.Microsoft.Alpha(Opacity=', 'alpha(opacity=', $body);

        // Shorten colors from rgb(51,102,153) to #336699, rgb(100%,0%,0%) to #ff0000 (sRGB color space)
        // Shorten colors from hsl(0, 100%, 50%) to #ff0000 (sRGB color space)
        // This makes it more likely that it'll get further compressed in the next step.
        $body = preg_replace_callback(
            '/(rgb|hsl)\(([0-9,.% -]+)\)(.|$)/Si',
            array($this, 'shortenHslAndRgbToHexCallback'),
            $body
        );

        // Shorten colors from #AABBCC to #ABC or shorter color name:
        // - Look for hex colors which don't have a "=" in front of them (to avoid MSIE filters)
        $body = preg_replace_callback(
            '/(?<!=)#([0-9a-f]{3,6})( |,|\)|;|$)/Si',
            array($this, 'shortenHexColorsCallback'),
            $body
        );

        // Shorten long named colors with a shorter HEX counterpart: white -> #fff.
        // Run at least 2 times to cover most cases
        $body = preg_replace_callback(
            array($this->namedToHexColorsRegex, $this->namedToHexColorsRegex),
            array($this, 'shortenNamedColorsCallback'),
            $body
        );

        // Replace positive sign from numbers before the leading space is removed.
        // +1.2em to 1.2em, +.8px to .8px, +2% to 2%
        $body = preg_replace('/([ :,(])\+(\.?\d+)/S', '$1$2', $body);

        // shorten ms to s
        $body = preg_replace_callback('/([ :,(])(-?)(\d{3,})ms/Si', function ($matches) {
            return $matches[1] . $matches[2] . ((int) $matches[3] / 1000) .'s';
        }, $body);

        // Remove leading zeros from integer and float numbers.
        // 000.6 to .6, -0.8 to -.8, 0050 to 50, -01.05 to -1.05
        $body = preg_replace('/([ :,(])(-?)0+([1-9]?\.?\d+)/S', '$1$2$3', $body);

        // Remove trailing zeros from float numbers.
        // -6.0100em to -6.01em, .0100 to .01, 1.200px to 1.2px
        $body = preg_replace('/([ :,(])(-?\d?\.\d+?)0+([^\d])/S', '$1$2$3', $body);

        // Remove trailing .0 -> -9.0 to -9
        $body = preg_replace('/([ :,(])(-?\d+)\.0([^\d])/S', '$1$2$3', $body);

        // Replace 0 length numbers with 0
        $body = preg_replace('/([ :,(])-?\.?0+([^\d])/S', '${1}0$2', $body);

        // Shorten zero values for safe properties only
        $body = preg_replace(
            array(
                $this->shortenOneZeroesRegex,
                $this->shortenTwoZeroesRegex,
                $this->shortenThreeZeroesRegex,
                $this->shortenFourZeroesRegex
            ),
            array(
                '$1$2:0',
                '$1$2:$3 0',
                '$1$2:$3 $4 0',
                '$1$2:$3 $4 $5 0'
            ),
            $body
        );

        // Replace 0 0 0; or 0 0 0 0; with 0 0 for background-position property.
        $body = preg_replace('/(background-position):0(?: 0){2,3}( !|;|$)/Si', '$1:0 0$2', $body);

        // Shorten suitable shorthand properties with repeated values
        $body = preg_replace(
            array(
                '/(margin|padding|border-(?:width|radius)):('.$this->numRegex.')(?: \2)+( !|;|$)/Si',
                '/(border-(?:style|color)):([#a-z0-9]+)(?: \2)+( !|;|$)/Si'
            ),
            '$1:$2$3',
            $body
        );
        $body = preg_replace(
            array(
                '/(margin|padding|border-(?:width|radius)):'.
                '('.$this->numRegex.') ('.$this->numRegex.') \2 \3( !|;|$)/Si',
                '/(border-(?:style|color)):([#a-z0-9]+) ([#a-z0-9]+) \2 \3( !|;|$)/Si'
            ),
            '$1:$2 $3$4',
            $body
        );
        $body = preg_replace(
            array(
                '/(margin|padding|border-(?:width|radius)):'.
                '('.$this->numRegex.') ('.$this->numRegex.') ('.$this->numRegex.') \3( !|;|$)/Si',
                '/(border-(?:style|color)):([#a-z0-9]+) ([#a-z0-9]+) ([#a-z0-9]+) \3( !|;|$)/Si'
            ),
            '$1:$2 $3 $4$5',
            $body
        );

        // Lowercase some common functions that can be values
        $body = preg_replace_callback(
            '/(?:attr|blur|brightness|circle|contrast|cubic-bezier|drop-shadow|ellipse|from|grayscale|'.
            'hsla?|hue-rotate|inset|invert|local|minmax|opacity|perspective|polygon|rgba?|rect|repeat|saturate|sepia|'.
            'steps|to|url|var|-webkit-gradient|'.
            '(?:-(?:atsc|khtml|moz|ms|o|wap|webkit)-)?(?:calc|(?:repeating-)?(?:linear|radial)-gradient))\(/Si',
            array($this, 'strtolowerCallback'),
            $body
        );

        // Lowercase all uppercase properties
        $body = preg_replace_callback('/(?:^|;)[A-Z-]+:/S', array($this, 'strtolowerCallback'), $body);

        return $body;
    }

    /**
     * Compresses At-rules and selectors.
     * @param string $css the whole stylesheet with rule bodies tokenized.
     * @return string
     */
    private function processAtRulesAndSelectors($css)
    {
        $charset = '';
        $imports = '';
        $namespaces = '';

        // Remove spaces before the things that should not have spaces before them.
        $css = preg_replace('/ ([@{};>+)\]~=,\/\n])/S', '$1', $css);

        // Remove the spaces after the things that should not have spaces after them.
        $css = preg_replace('/([{}:;>+(\[~=,\/\n]) /S', '$1', $css);

        // Shorten shortable double colon (CSS3) pseudo-elements to single colon (CSS2)
        $css = preg_replace('/::(before|after|first-(?:line|letter))(\{|,)/Si', ':$1$2', $css);

        // Retain space for special IE6 cases
        $css = preg_replace_callback('/:first-(line|letter)(\{|,)/Si', function ($matches) {
            return ':first-'. strtolower($matches[1]) .' '. $matches[2];
        }, $css);

        // Find a fraction that may used in some @media queries such as: (min-aspect-ratio: 1/1)
        // Add token to add the "/" back in later
        $css = preg_replace('/\(([a-z-]+):([0-9]+)\/([0-9]+)\)/Si', '($1:$2'. self::QUERY_FRACTION .'$3)', $css);

        // Remove empty rule blocks up to 2 levels deep.
        $css = preg_replace(array_fill(0, 2, '/(\{)[^{};\/\n]+\{\}/S'), '$1', $css);
        $css = preg_replace('/[^{};\/\n]+\{\}/S', '', $css);

        // Two important comments next to each other? Remove extra newline.
        if ($this->keepImportantComments) {
            $css = str_replace("\n\n", "\n", $css);
        }

        // Restore fraction
        $css = str_replace(self::QUERY_FRACTION, '/', $css);

        // Lowercase some popular @directives
        $css = preg_replace_callback(
            '/(?<!\\\\)@(?:charset|document|font-face|import|(?:-(?:atsc|khtml|moz|ms|o|wap|webkit)-)?keyframes|media|'.
            'namespace|page|supports|viewport)/Si',
            array($this, 'strtolowerCallback'),
            $css
        );

        // Lowercase some popular media types
        $css = preg_replace_callback(
            '/[ ,](?:all|aural|braille|handheld|print|projection|screen|tty|tv|embossed|speech)[ ,;{]/Si',
            array($this, 'strtolowerCallback'),
            $css
        );

        // Lowercase some common pseudo-classes & pseudo-elements
        $css = preg_replace_callback(
            '/(?<!\\\\):(?:active|after|before|checked|default|disabled|empty|enabled|first-(?:child|of-type)|'.
            'focus(?:-within)?|hover|indeterminate|in-range|invalid|lang\(|last-(?:child|of-type)|left|link|not\(|'.
            'nth-(?:child|of-type)\(|nth-last-(?:child|of-type)\(|only-(?:child|of-type)|optional|out-of-range|'.
            'read-(?:only|write)|required|right|root|:selection|target|valid|visited)/Si',
            array($this, 'strtolowerCallback'),
            $css
        );

        // @charset handling
        if (preg_match($this->charsetRegex, $css, $matches)) {
            // Keep the first @charset at-rule found
            $charset = $matches[0];
            // Delete all @charset at-rules
            $css = preg_replace($this->charsetRegex, '', $css);
        }

        // @import handling
        $css = preg_replace_callback($this->importRegex, function ($matches) use (&$imports) {
            // Keep all @import at-rules found for later
            $imports .= $matches[0];
            // Delete all @import at-rules
            return '';
        }, $css);

        // @namespace handling
        $css = preg_replace_callback($this->namespaceRegex, function ($matches) use (&$namespaces) {
            // Keep all @namespace at-rules found for later
            $namespaces .= $matches[0];
            // Delete all @namespace at-rules
            return '';
        }, $css);

        // Order critical at-rules:
        // 1. @charset first
        // 2. @imports below @charset
        // 3. @namespaces below @imports
        $css = $charset . $imports . $namespaces . $css;

        return $css;
    }

    /**
     * Splits long lines after a specific column.
     *
     * Some source control tools don't like it when files containing lines longer
     * than, say 8000 characters, are checked in. The linebreak option is used in
     * that case to split long lines after a specific column.
     *
     * @param string $css the whole stylesheet.
     * @return string
     */
    private function processLongLineSplitting($css)
    {
        if ($this->linebreakPosition > 0) {
            $l = strlen($css);
            $offset = $this->linebreakPosition;
            while (preg_match('/(?<!\\\\)\}(?!\n)/S', $css, $matches, PREG_OFFSET_CAPTURE, $offset)) {
                $matchIndex = $matches[0][1];
                $css = substr_replace($css, "\n", $matchIndex + 1, 0);
                $offset = $matchIndex + 2 + $this->linebreakPosition;
                $l += 1;
                if ($offset > $l) {
                    break;
                }
            }
        }

        return $css;
    }

    /**
     * Converts hsl() & rgb() colors to HEX format.
     * @param $matches
     * @return string
     */
    private function shortenHslAndRgbToHexCallback($matches)
    {
        $type = $matches[1];
        $values = explode(',', $matches[2]);
        $terminator = $matches[3];

        if ($type === 'hsl') {
            $values = Utils::hslToRgb($values);
        }

        $hexColors = Utils::rgbToHex($values);

        // Restore space after rgb() or hsl() function in some cases such as:
        // background-image: linear-gradient(to bottom, rgb(210,180,140) 10%, rgb(255,0,0) 90%);
        if (!empty($terminator) && !preg_match('/[ ,);]/S', $terminator)) {
            $terminator = ' '. $terminator;
        }

        return '#'. implode('', $hexColors) . $terminator;
    }

    /**
     * Compresses HEX color values of the form #AABBCC to #ABC or short color name.
     * @param $matches
     * @return string
     */
    private function shortenHexColorsCallback($matches)
    {
        $hex = $matches[1];

        // Shorten suitable 6 chars HEX colors
        if (strlen($hex) === 6 && preg_match('/^([0-9a-f])\1([0-9a-f])\2([0-9a-f])\3$/Si', $hex, $m)) {
            $hex = $m[1] . $m[2] . $m[3];
        }

        // Lowercase
        $hex = '#'. strtolower($hex);

        // Replace Hex colors with shorter color names
        $color = array_key_exists($hex, $this->hexToNamedColorsMap) ? $this->hexToNamedColorsMap[$hex] : $hex;

        return $color . $matches[2];
    }

    /**
     * Shortens all named colors with a shorter HEX counterpart for a set of safe properties
     * e.g. white -> #fff
     * @param array $matches
     * @return string
     */
    private function shortenNamedColorsCallback($matches)
    {
        return $matches[1] . $this->namedToHexColorsMap[strtolower($matches[2])] . $matches[3];
    }

    /**
     * Makes a string lowercase
     * @param array $matches
     * @return string
     */
    private function strtolowerCallback($matches)
    {
        return strtolower($matches[0]);
    }
}

class Utils
{
    /**
     * Clamps a number between a minimum and a maximum value.
     * @param int|float $n the number to clamp
     * @param int|float $min the lower end number allowed
     * @param int|float $max the higher end number allowed
     * @return int|float
     */
    public static function clampNumber($n, $min, $max)
    {
        return min(max($n, $min), $max);
    }

    /**
     * Clamps a RGB color number outside the sRGB color space
     * @param int|float $n the number to clamp
     * @return int|float
     */
    public static function clampNumberSrgb($n)
    {
        return self::clampNumber($n, 0, 255);
    }

    /**
     * Converts a HSL color into a RGB color
     * @param array $hslValues
     * @return array
     */
    public static function hslToRgb($hslValues)
    {
        $h = floatval($hslValues[0]);
        $s = floatval(str_replace('%', '', $hslValues[1]));
        $l = floatval(str_replace('%', '', $hslValues[2]));

        // Wrap and clamp, then fraction!
        $h = ((($h % 360) + 360) % 360) / 360;
        $s = self::clampNumber($s, 0, 100) / 100;
        $l = self::clampNumber($l, 0, 100) / 100;

        if ($s == 0) {
            $r = $g = $b = self::roundNumber(255 * $l);
        } else {
            $v2 = $l < 0.5 ? $l * (1 + $s) : ($l + $s) - ($s * $l);
            $v1 = (2 * $l) - $v2;
            $r = self::roundNumber(255 * self::hueToRgb($v1, $v2, $h + (1/3)));
            $g = self::roundNumber(255 * self::hueToRgb($v1, $v2, $h));
            $b = self::roundNumber(255 * self::hueToRgb($v1, $v2, $h - (1/3)));
        }

        return array($r, $g, $b);
    }

    /**
     * Tests and selects the correct formula for each RGB color channel
     * @param $v1
     * @param $v2
     * @param $vh
     * @return mixed
     */
    public static function hueToRgb($v1, $v2, $vh)
    {
        $vh = $vh < 0 ? $vh + 1 : ($vh > 1 ? $vh - 1 : $vh);

        if ($vh * 6 < 1) {
            return $v1 + ($v2 - $v1) * 6 * $vh;
        }

        if ($vh * 2 < 1) {
            return $v2;
        }

        if ($vh * 3 < 2) {
            return $v1 + ($v2 - $v1) * ((2 / 3) - $vh) * 6;
        }

        return $v1;
    }

    /**
     * Convert strings like "64M" or "30" to int values
     * @param mixed $size
     * @return int
     */
    public static function normalizeInt($size)
    {
        if (is_string($size)) {
            $letter = substr($size, -1);
            $size = intval($size);
            switch ($letter) {
                case 'M':
                case 'm':
                    return (int) $size * 1048576;
                case 'K':
                case 'k':
                    return (int) $size * 1024;
                case 'G':
                case 'g':
                    return (int) $size * 1073741824;
            }
        }
        return (int) $size;
    }

    /**
     * Converts a string containing and RGB percentage value into a RGB integer value i.e. '90%' -> 229.5
     * @param $rgbPercentage
     * @return int
     */
    public static function rgbPercentageToRgbInteger($rgbPercentage)
    {
        if (strpos($rgbPercentage, '%') !== false) {
            $rgbPercentage = self::roundNumber(floatval(str_replace('%', '', $rgbPercentage)) * 2.55);
        }

        return intval($rgbPercentage, 10);
    }

    /**
     * Converts a RGB color into a HEX color
     * @param array $rgbColors
     * @return array
     */
    public static function rgbToHex($rgbColors)
    {
        $hexColors = array();

        // Values outside the sRGB color space should be clipped (0-255)
        for ($i = 0, $l = count($rgbColors); $i < $l; $i++) {
            $hexColors[$i] = sprintf("%02x", self::clampNumberSrgb(self::rgbPercentageToRgbInteger($rgbColors[$i])));
        }

        return $hexColors;
    }

    /**
     * Rounds a number to its closest integer
     * @param $n
     * @return int
     */
    public static function roundNumber($n)
    {
        return intval(round(floatval($n)), 10);
    }
}

class Colors
{
    public static function getHexToNamedMap()
    {
        // Hex colors longer than named counterpart
        return array(
            '#f0ffff' => 'azure',
            '#f5f5dc' => 'beige',
            '#ffe4c4' => 'bisque',
            '#a52a2a' => 'brown',
            '#ff7f50' => 'coral',
            '#ffd700' => 'gold',
            '#808080' => 'gray',
            '#008000' => 'green',
            '#4b0082' => 'indigo',
            '#fffff0' => 'ivory',
            '#f0e68c' => 'khaki',
            '#faf0e6' => 'linen',
            '#800000' => 'maroon',
            '#000080' => 'navy',
            '#fdf5e6' => 'oldlace',
            '#808000' => 'olive',
            '#ffa500' => 'orange',
            '#da70d6' => 'orchid',
            '#cd853f' => 'peru',
            '#ffc0cb' => 'pink',
            '#dda0dd' => 'plum',
            '#800080' => 'purple',
            '#f00'    => 'red',
            '#fa8072' => 'salmon',
            '#a0522d' => 'sienna',
            '#c0c0c0' => 'silver',
            '#fffafa' => 'snow',
            '#d2b48c' => 'tan',
            '#008080' => 'teal',
            '#ff6347' => 'tomato',
            '#ee82ee' => 'violet',
            '#f5deb3' => 'wheat'
        );
    }

    public static function getNamedToHexMap()
    {
        // Named colors longer than hex counterpart
        return array(
            'aliceblue' => '#f0f8ff',
            'antiquewhite' => '#faebd7',
            'aquamarine' => '#7fffd4',
            'black' => '#000',
            'blanchedalmond' => '#ffebcd',
            'blueviolet' => '#8a2be2',
            'burlywood' => '#deb887',
            'cadetblue' => '#5f9ea0',
            'chartreuse' => '#7fff00',
            'chocolate' => '#d2691e',
            'cornflowerblue' => '#6495ed',
            'cornsilk' => '#fff8dc',
            'darkblue' => '#00008b',
            'darkcyan' => '#008b8b',
            'darkgoldenrod' => '#b8860b',
            'darkgray' => '#a9a9a9',
            'darkgreen' => '#006400',
            'darkgrey' => '#a9a9a9',
            'darkkhaki' => '#bdb76b',
            'darkmagenta' => '#8b008b',
            'darkolivegreen' => '#556b2f',
            'darkorange' => '#ff8c00',
            'darkorchid' => '#9932cc',
            'darksalmon' => '#e9967a',
            'darkseagreen' => '#8fbc8f',
            'darkslateblue' => '#483d8b',
            'darkslategray' => '#2f4f4f',
            'darkslategrey' => '#2f4f4f',
            'darkturquoise' => '#00ced1',
            'darkviolet' => '#9400d3',
            'deeppink' => '#ff1493',
            'deepskyblue' => '#00bfff',
            'dodgerblue' => '#1e90ff',
            'firebrick' => '#b22222',
            'floralwhite' => '#fffaf0',
            'forestgreen' => '#228b22',
            'fuchsia' => '#f0f',
            'gainsboro' => '#dcdcdc',
            'ghostwhite' => '#f8f8ff',
            'goldenrod' => '#daa520',
            'greenyellow' => '#adff2f',
            'honeydew' => '#f0fff0',
            'indianred' => '#cd5c5c',
            'lavender' => '#e6e6fa',
            'lavenderblush' => '#fff0f5',
            'lawngreen' => '#7cfc00',
            'lemonchiffon' => '#fffacd',
            'lightblue' => '#add8e6',
            'lightcoral' => '#f08080',
            'lightcyan' => '#e0ffff',
            'lightgoldenrodyellow' => '#fafad2',
            'lightgray' => '#d3d3d3',
            'lightgreen' => '#90ee90',
            'lightgrey' => '#d3d3d3',
            'lightpink' => '#ffb6c1',
            'lightsalmon' => '#ffa07a',
            'lightseagreen' => '#20b2aa',
            'lightskyblue' => '#87cefa',
            'lightslategray' => '#778899',
            'lightslategrey' => '#778899',
            'lightsteelblue' => '#b0c4de',
            'lightyellow' => '#ffffe0',
            'limegreen' => '#32cd32',
            'mediumaquamarine' => '#66cdaa',
            'mediumblue' => '#0000cd',
            'mediumorchid' => '#ba55d3',
            'mediumpurple' => '#9370db',
            'mediumseagreen' => '#3cb371',
            'mediumslateblue' => '#7b68ee',
            'mediumspringgreen' => '#00fa9a',
            'mediumturquoise' => '#48d1cc',
            'mediumvioletred' => '#c71585',
            'midnightblue' => '#191970',
            'mintcream' => '#f5fffa',
            'mistyrose' => '#ffe4e1',
            'moccasin' => '#ffe4b5',
            'navajowhite' => '#ffdead',
            'olivedrab' => '#6b8e23',
            'orangered' => '#ff4500',
            'palegoldenrod' => '#eee8aa',
            'palegreen' => '#98fb98',
            'paleturquoise' => '#afeeee',
            'palevioletred' => '#db7093',
            'papayawhip' => '#ffefd5',
            'peachpuff' => '#ffdab9',
            'powderblue' => '#b0e0e6',
            'rebeccapurple' => '#663399',
            'rosybrown' => '#bc8f8f',
            'royalblue' => '#4169e1',
            'saddlebrown' => '#8b4513',
            'sandybrown' => '#f4a460',
            'seagreen' => '#2e8b57',
            'seashell' => '#fff5ee',
            'slateblue' => '#6a5acd',
            'slategray' => '#708090',
            'slategrey' => '#708090',
            'springgreen' => '#00ff7f',
            'steelblue' => '#4682b4',
            'turquoise' => '#40e0d0',
            'white' => '#fff',
            'whitesmoke' => '#f5f5f5',
            'yellow' => '#ff0',
            'yellowgreen' => '#9acd32'
        );
    }
}