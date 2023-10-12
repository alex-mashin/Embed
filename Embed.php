<?php
declare(strict_types=1);
/*
 * Embed.php - Adds a parser function embedding video and audio from popular sources.
 * @author Mithgol the Webmaster, based on the work by Jim R. Wilson (EmbedVideo) and contributions of Alex Mashin
 * @version 0.3
 * @copyright (C) 2007 Jim R. Wilson, (C) 2008-2010 Mithgol the Webmaster, (C) 2010, 2012-2015 Alex Mashin
 * @license The MIT License - http://www.opensource.org/licenses/mit-license.php
 * ------------------------------------------------------------------------------------------------------
 * Description:
 *     This is a MediaWiki extension which adds a parser function for embedding
 *     video and audio from popular sources (configurable).
 * Requirements:
 *     MediaWiki 1.6.x, 1.9.x, 1.10.x or higher
 *     PHP 4.x, 5.x or higher.
 * Version Notes:
 *     version 0.4:
 *         CSP header is enabled.
 *     version 0.3:
 *         HTTPS enabled.
 *     version 0.2.2:
 *         Width in percents.
 *     version 0.2.1:
 *         Empty height.
 *     version 0.2:
 *         Renamed EmbedVideo to Embed, moved services settings to messages, deeply refactored code.
 *     version 0.1.4.Pogrebnoj:
 *         Added rpod.ru.
 *     version 0.1.3:
 *         Updated Vesti's movie URL; deprecated wfLoadExtensionMessages () call removed.
 *     version 0.1.2.Mashin.28:
 *         Enabled VKontakte video in Mozilla.
 *     version 0.1.2.Mashin.27:
 *         Added VKontakte video.
 *     version 0.1.2.Mashin.26:
 *         Removed a fatal error under MW 1.17 (lines 163-164).
 *     version 0.1.2.Mithgol.25:
 *         Added &modestbranding=1 to both of YouTube codes;
 *         see http://habrahabr.ru/blogs/youtube/121188/ for details.
 *         YouTube player is 480?390 (normal) or 640?390 (wide) by default, as YouTube code suggests now,
 *         and its controls_height is 30, because version=3 is appended;
 *         see http://code.google.com/apis/youtube/player_parameters.html for details.
 *     version 0.1.2.Mashin.24:
 *         Added ustream.tv.
 *     version 0.1.2.Mithgol.23:
 *         Cosmetic fixes of version 0.1.2.Mashin.23.
 *     version 0.1.2.Mashin.23:
 *         Added support for MediaWiki 1.16 (changed message caching).
 *     version 0.1.2.Mithgol.22:
 *         Added support for NewsTube.Ru video.
 *     version 0.1.2.Mithgol.21:
 *         YouTube player is 480?385 (normal) or 640?385 (wide) by default, as YouTube code suggests now.
 *     version 0.1.2.Mithgol.20:
 *         Added support for http://vimeo.com/ video.
 *     version 0.1.2.Mithgol.19:
 *         Added yet another Muzicons audio player from http://muzicons.com/
 *         in order to play MP3 files uploaded to Traditio.
 *     version 0.1.2.Mithgol.18:
 *         Added support for http://www.slideshare.net/ documents and presentations.
 *         Added support for http://www.divshare.com/ audio.
 *     version 0.1.2.Mithgol.17:
 *         Added support for http://blip.tv/ video.
 *     version 0.1.2.Mithgol.16:
 *         Added support for http://www.vesti.ru/ news video.
 *     version 0.1.2.Mithgol.15:
 *         Added support for http://www.1tv.ru/ news video.
 *     version 0.1.2.Mithgol.14:
 *         Set correct controls_height for Video.Mail.Ru.
 *     version 0.1.2.Mithgol.13:
 *         Enabled support for Video.Mail.Ru.
 *     version 0.1.2.Mithgol.12:
 *         Bugfixes, coding style fixes, security fixes.
 *     version 0.1.2.Mithgol.11:
 *         Bugfixes for Gigapan and the description.
 *     version 0.1.2.Mithgol.10:
 *         Values of ['id_regex'] and $idpattern now contain positive filters, not negative.
 *         Added a photo panorama provider (Gigapan), see http://gigapan.org/ for details.
 *     version 0.1.2.Mithgol.9:
 *         Added fullscreen abilities to OBJECT parameters and EMBED attributes.
 *     version 0.1.2.Mithgol.8:
 *         Added yet another kind of YouTube player: the wide one, for HD video (480x295 player, 16:9 video).
 *     version 0.1.2.Mithgol.7:
 *         Reflected the new YouTube player height (425x344 player, 4:3 video).
 *     version 0.1.2.Mithgol.6:
 *         BUGFIX: the site http://muzicons.com/ offers a bogus BB-code with 135x30 player,
 *         but actual dimesions is 150?50 (see their HTML code).
 *     version 0.1.2.Mithgol.5:
 *         Added Muzicons audio player from http://muzicons.com/
 *     version 0.1.2.Mithgol.4:
 *         Reflected the new RuTube player width (470 instead of former 400).
 *
 *         Reflected the new imeem player dimensions (300?340 playlists, 300?110 audio tracks).
 *
 *         Some players (like Google Video and YouTube) have constant height of video controls,
 *         and thus (width / (height - controlsHeight)) should be used instead of (width / height)
 *         when calculating the aspect ratio of the video.
 *
 *         Added yet another audio content provider: Box.net/lite
 *
 *         SECURITY NOTE: as http://tinyurl.com/5jpu3s says, the default value of AllowScriptAccess
 *         is 'sameDomain', so the remote Flash objects cannot communicate with our Tradtio pages,
 *         steal cookies, etc.; thus we are safe to add _any_ external Flash content providers.
 *     version 0.1.2.Mithgol.3:
 *         Now plays imeem audio with «-» or «_» in code.
 *     version 0.1.2.Mithgol.2:
 *         Fixed YouTube videos (they now require &rel=1).
 *     version 0.1.2.Mithgol.1:
 *         Bug fix: does not require width / height to be 425 / 350.
 *         (Now supports imeem.com audio, and YouTube does not glitch.)
 *         List of services is altered accommodating to the new requirements
 *         and general popularity of services across Traditio.Ru
 *     version 0.1.2:
 *         Bug fix: now can be inserted in lists without breakage (from newlines)
 *         Code cleanup: would previously give 'Notice' messages in PHP strict.
 *     version 0.1.1:
 *         Code cleanup: no functional difference.
 *     version 0.1:
 *         Initial release.
 * -----------------------------------------------------------------------
 * Copyright (c) 2007 Jim R. Wilson
 * Copyright (c) 2008-2010 Mithgol the Webmaster
 * Copyright (c) 2010, 2012-2015 Alexander Mashin
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 * -----------------------------------------------------------------------
 */
use MediaWiki\MediaWikiServices;

// Confirm MW environment
if ( !defined( 'MEDIAWIKI' ) ) die();

/**
 * Wrapper class for encapsulating Embed related parser methods
 */
class Embed {
	/**
	 * Sets up parser functions.
	 *
	 * @param Parser $parser The Parser object.
	 * @return bool
	 */
	public static function setup( Parser &$parser ): bool {
		// Add all domains to CSP header:
		$csp_domains = [];
		$csp_explicit =  wfMessage( 'embed-csp' )->text();
		if ( $csp_explicit ) {
			$csp_domains = explode( ',', $csp_explicit );
		} else {
			$prefix = 'embed-';
			$prefix_length = strlen( $prefix );
			$suffix = '-url';
			$suffix_length = -strlen( $suffix );
			foreach  (MediaWikiServices::getInstance()->getLocalisationCache()->getSubitemList( 'en', 'messages' ) as $key ) {
				$key = strtolower( $key );
				if ( substr( $key, 0, $prefix_length ) === $prefix && substr( $key, $suffix_length ) === $suffix ) {
					$url = wfMessage( $key, [] )->text();
					// Add 2nd-level domain to CSP Header:
					$chunks = array_reverse( explode( '.', parse_url( $url,  PHP_URL_HOST ) ) );
					$csp_domains[] = $chunks[1] . '.' . $chunks[0];
					// Additional domains:
					$service = substr( $key, $prefix_length, $suffix_length );
					$csp_domains += explode ( ',', self::setting( $service, 'csp' ) );
				} // -- if ( substr( $key, 0, $prefix_length ) === $prefix && substr( $key, $suffix_length ) === $suffix )
			} // -- foreach ( $message_cache->getAllMessageKeys( $lang ) as $key )
		} // -- if ( !$csp_explicit->isBlank() )
		self::addCSP( $csp_domains );

		// Setup parser hook:
		$parser->setFunctionHook( 'embed', [ self::class, 'parserFunction' ] );
		return true;
	}    // -- public static function setup (Parser &$parser): bool

	/**
 	 * Add domains with their subdomains to CSP header. 
   	 * @param array $domains Domains to add.
     	 */
	private static function addCSP( array $domains ) {
		global $wgCSPHeader;
		if ( $wgCSPHeader === false ) {
			return;
		}
		if ( $wgCSPHeader === true ) {
			$wgCSPHeader = [];
		}
		foreach ( [ 'frame-src', 'script-src', 'default-src' ] as $src ) {
			if ( !is_array( $wgCSPHeader[$src] ) ) {
				$wgCSPHeader[$src] = [];
			}
			foreach ( $domains as $domain ) {
				$domain = trim( $domain );
				foreach ( [ $domain, '*.' . $domain ] as $domain2add ) {
					if ( !in_array( $domain2add, $wgCSPHeader[$src], true ) ) {
						$wgCSPHeader[$src][] = $domain2add;
					}
				}
			} // -- foreach ( $domains as $domain )
		} // -- foreach ( [ 'frame-src', 'script-src', 'default-src' ] as $src )
	} // -- private static function addCSP( string $ )

	/**
	 * Embeds video of the chosen service
	 *
	 * @param Parser $parser Instance of running Parser.
	 * @param ?string $service Which online service has the video.
	 * @param ?string $id Identifier of the chosen service
	 * @param ?string $width Width of video (optional)
	 * @param ?string $height Height of video (optional)
	 * @param ?string $param4 Additional parametre (optional)
	 * @param ?string $param5 Additional parametre (optional)
	 * @return string           Wikicode of the embedded multimedia
	 */
	public static function parserFunction(
		Parser $parser,
		?string $service = null,
		?string $id = null,
		?string $width = null,
		?string $height = null,
		?string $param4 = null,
		?string $param5 = null
	): string {
		// Missing two first parametres:
		if ( $service === null || $id === null ) {
			return '<div class="errorbox">' . wfMessage( 'embed-missing-params' )->parse() . '</div>';
		}

		$service = @htmlspecialchars( trim( $service ) );

		global $wgEmbedMinWidth, $wgEmbedMaxWidth;
		if ( !is_numeric( $wgEmbedMinWidth ) || $wgEmbedMinWidth < 100 ) $wgEmbedMinWidth = 100;
		if ( !is_numeric( $wgEmbedMaxWidth ) || $wgEmbedMaxWidth > 2048 ) $wgEmbedMaxWidth = 2048;

		global $wgEmbedMinHeight, $wgEmbedMaxHeight;
		if ( !is_numeric( $wgEmbedMinHeight ) || $wgEmbedMinHeight < 0 ) $wgEmbedMinHeight = 0;
		if ( !is_numeric( $wgEmbedMaxHeight ) || $wgEmbedMaxHeight > 2048 ) $wgEmbedMaxHeight = 2048;

		// Checking if there is an URL message for the service:
		if ( wfMessage( "embed-$service-url" )->isBlank() ) {
			return '<div class="errorbox">' . wfMessage( 'embed-unrecognized-service', $service )->parse() . '</div>';
		}

		// Checking id:
		$id = trim( $id );
		if ( !self::check( $service, 'id', $id ) ) {
			return '<div class="errorbox">'
				 . wfMessage( 'embed-bad-id', 'id', @htmlspecialchars( $id ), $service )->parse()
				 . '</div>';
		}

		// Getting default size:
		$defaultWidth = self::setting( $service, 'default_width' );
		$defaultHeight = self::setting( $service, 'default_height' );
		$controlsHeight = self::setting( $service, 'controls_height' );

		/*
		 *  Build URL and output embedded flash object:
		 */
		if ( is_numeric( $defaultWidth ) ) {
			$ratio = ($defaultHeight - $controlsHeight) / $defaultWidth;
		} else {
			$ratio = 0;
		}

		$width = $width === null ? null : @htmlspecialchars( trim( $width ) );
		$height = $height === null ? null : @htmlspecialchars( trim( $height ) );

		if ( $width !== null && $width !== '' && $width !== 0 ) {
			// Check whether $width is invalid:
			if ( $width < $wgEmbedMinWidth || $width > $wgEmbedMaxWidth	) {
				return '<div class="errorbox">'
					. wfMessage( 'embed-illegal-width', $width )->parse()
					. '</div>';
			}
		} else {
			$width = $defaultWidth;
		}

		if ( $height !== null && $height !== '' && $height !== 0 ) {
			// Check whether $height is invalid:
			if ( !is_numeric( $height )	|| $height < $wgEmbedMinHeight || $height > $wgEmbedMaxHeight ) {
				return '<div class="errorbox">'
					. wfMessage( 'embed-illegal-height', $height )->parse()
					. '</div>';
			}
		} else {
			$height = round( $width * $ratio ) + $controlsHeight;
		}

		$url = self::setting( $service, 'url', [ $id, $width, $height, $param4, $param5 ] );

		return $parser->insertStripItem( self::setting( $service, 'code', [ $url, $width, $height, $id ] ) );
	}    // -- public static function parserFunction (...): bool

	/**
	 * Returns the value of a setting
	 *
	 * @param string $service For which service to return a setting.
	 * @param string $setting Which setting to return.
	 * @param ?array $params Parametres for the MediaWiki messsage.
	 * @return string            The setting value
	 */
	private static function setting( string $service, string $setting, ?array $params = [] ): string {
		// First, try to find setting for the service:
		$msg = wfMessage( "embed-$service-$setting", $params );
		// Then try to find a default value:
		$msg = $msg->isBlank() ? wfMessage( "embed-default-$setting", $params ) : $msg;
		// Do not escape regexes:
		return $setting === 'id_regex' ? $msg->plain() : $msg->text();
	}    // -- private static function setting (string $service, string $setting, array $params = []): string

	/**
	 * Returns true if the parametre $name with the value $value fits the regex for it for $service.
	 *
	 * @param string $service Service id.
	 * @param string $name Parametre name.
	 * @param string $value Parametre value.
	 * @return bool
	 */
	private static function check( string $service, string $name, string $value ): bool {
		// Getting parametre pattern from messages:
		$regex = self::setting( $service, "regex_$name" );
		// Sanitising regex options. The only allowed ones are i and x:
		preg_match( '/^(.)(.*)\1([a-zA-Z]*)$/', $regex, $matches );
		$options = '';
		if ( strpos( $matches [3], 'i' ) ) $options .= 'i';
		if ( strpos( $matches [3], 'x' ) ) $options .= 'x';
		$regex = $matches [1] . $matches [2] . $matches [1] . $options;
		return $value && preg_match( $regex, $value );
	}   // -- private static function check( string $service, string $name, string $value ): bool
}
