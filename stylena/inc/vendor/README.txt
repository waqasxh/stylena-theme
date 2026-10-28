Bundled third-party libraries
=============================

TGM Plugin Activation (TGMPA)
- File:    class-tgm-plugin-activation.php (one local change, see below)
- Version: 2.6.1
- Licence: GPL-2.0+ (see LICENSE-TGMPA.md)
- Authors: Thomas Griffin, Gary Jones, Juliette Reinders Folmer
- Source:  https://github.com/TGMPA/TGM-Plugin-Activation/tree/2.6.1

Local changes (re-apply after updating TGMPA)
- class-tgm-plugin-activation.php: ABSPATH direct-access guard
  (if ( ! defined( 'ABSPATH' ) ) { exit; }) added after the licence header,
  before the class_exists( 'TGM_Plugin_Activation' ) check. No other changes.
