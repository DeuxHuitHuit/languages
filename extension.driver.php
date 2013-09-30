<?php



	if( !defined( '__IN_SYMPHONY__' ) ) die('<h2>Symphony Error</h2><p>You cannot directly access this file</p>');



	require_once EXTENSIONS.'/languages/lib/class.langman.php';



	class Extension_Languages extends Extension
	{

		public $field_table = 'tbl_fields_languages';



		/*------------------------------------------------------------------------------------------------*/
		/*  Installation  */
		/*------------------------------------------------------------------------------------------------*/

		public function install(){
			return Symphony::Database()->query( sprintf(
				"CREATE TABLE `%s` (
					`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					`field_id` INT(11) UNSIGNED NOT NULL,
					`available_codes` VARCHAR(255) NULL,
					`allow_multiple_selection` enum('yes','no') NOT NULL default 'no',
					PRIMARY KEY (`id`),
					KEY `field_id` (`field_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
				$this->field_table
			) );
		}

		public function uninstall(){
			try{
				Symphony::Database()->query( sprintf(
					"DROP TABLE `%s`",
					$this->field_table
				) );
			} catch( DatabaseException $dbe ){
				// table doesn't exist
			}
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Delegates  */
		/*------------------------------------------------------------------------------------------------*/


		public function getSubscribedDelegates(){
			return array(
				array(
					'page'     => '/frontend/',
					'delegate' => 'ManageEXSLFunctions',
					'callback' => 'dManageEXSLFunctions'
				),

			);
		}

		public function dManageEXSLFunctions($context){
			$context['manager']->addFunction(
				'Extension_Languages::fileExists',
				'extension_languages',
				'fileExists'
			);
		}



		/*------------------------------------------------------------------------------------------------*/
		/*  Public interface  */
		/*------------------------------------------------------------------------------------------------*/

		public static function fileExists($code){
			return is_file( LangMan::local()->getFileName($code) ) ? 1 : 0;
		}

	}
