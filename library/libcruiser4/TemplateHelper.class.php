<?php

	/**
	 * @package TemplateHelper
	 * @author Редькин Сергей, .ter [rou.terra@gmail.com]
	 * @copyright Cruiser cruiser.com.ua
	 */

	/**
	 * Класс вспомогательных методов для шаблонов
	 *
	 * @package TemplateHelper
	 */
	class TemplateHelper {

		/**
		 * Экземпляр класса
		 *
		 * @var TemplateHelper
		 */
		private static $_instance;

		/**
		 * @var mydb Database Layer
		 */
		private $_db;

		public function __construct() {
			global $db;

			$this->_db = $db;
		}

		/**
		 * getInstance
		 *
		 * @return DspHelper instance
		 */
		static public function getInstance() {
			if (!isset(self::$_instance)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Очистка данных шаблонов, подключенных к узлу сайта
		 *
		 * @param int $id_node
		 * @param int $type_te_page_site константа из таблицы site_tmpl_page_types
		 */
		public function clearTemplateLinkedToSiteNode($id_node, $type_te_page_site = 0) {
			if (!$id_node) return false;
			/* TODO !
			clearLngRecords(CFG_DBTBL_TE_EXECWCODE, array( 'id_map' => $id_node ), array( 'text' ), true);
			clearLngRecords(CFG_DBTBL_TE_EXECSCODE, array( 'id_map' => $id_node ), array( 'text' ), true);
			$this->_db->delete(CFG_DBTBL_TE_SELECTIVE_TMPL, array( 'id_map' => $id_node ));
			*/

			/**
			 * if (!$type_te_page_site) {
			 * 		// удаляем ВСЕ подлючения вне зависимости от шаблона
			 * }
			 */
			return true;
		}

		/**
		 * Удаление всех связей (с шаблонами), подключенных к узлу сайта
		 *
		 * @param int $id_node
		 */
		public function removeTemplateLinkedToSiteNode($id_node) {
			$this->_db->delete(CFG_DBTBL_SITE_TMPL_PAGE, array(
				'id_site_node' => $id_node
			));
		}

		/**
		 * Очистка связей и данных шаблона
		 *
		 * @param int id_template
		 */
		public function clearTemplateDataAndLinks($id_template) {
			if (!$id_template) return false;


		}

	}

