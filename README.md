# Embed extension
Version 0.4.

Alexander Mashin, Mithgol the Webmaster, based on work by Jim R. Wilson.
## Overview
*Embed* is a *MediaWiki* extension introducing a parser function `{{#embed:}}`
that allows to embed multimedia content into wiki pages.
## Credits
The extension is based on *[EmbedVideo](https://mediawiki.org/wiki/Extension:EmbedVideo)* *MediaWiki* extension
created by Jim R. Wilson and expanded by [Mithgol the Webmaster](https://traditio.wiki/Mithgol_the_Webmaster).
The new extension *Embed* is deeply refactored in 2013 by [Alexander Mashin](https://traditio.wiki/Alex_Mashin)
for [Traditio wiki](https://traditio.wiki). In 2021, it is published at GitHub.
## Requirements
The extension requires PHP 7.4 or higher to work, as well as MediaWiki 1.25 or higher.

Note that `tiktok` service requires *[External Data](https://mediawiki.org/wiki/Extension:ExternalData)* *MeidaWiki* extension to work.
## Installation
To install the extension, copy or the entire `Embed` folder or clone the repository into `(path to mediawiki)/extensions` folder.
To enable the extension, add `wfLoadExtension( 'Embed' );` to `LocalSettings.php`.
## Further information
For further information, see https://traditio.wiki/Embed.
