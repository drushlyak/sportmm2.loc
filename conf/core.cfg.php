<?php

	require_once ("project.cfg.php");
	require_once ("db.cfg.php");
	require_once ("memcache.cfg.php");

	define ('BASE_PATH',				dirname(dirname(__FILE__)));
	define ('MAIN_LIB_PATH',			BASE_PATH . "/library");
	define ('LIB_PATH',					BASE_PATH . "/library/libcruiser4");
	define ('MODULE_PATH',				BASE_PATH . "/virab/fbx/modules/");
	define ('DICT_PATH',				BASE_PATH . "/virab/fbx/dictionaries/");
	define ('RESOURCE_PATH',			BASE_PATH . "/resources");
	define ('HTDOCS_PATH',				BASE_PATH . "/htdocs");
	define ('UPLOAD_PATH',				BASE_PATH . "/upload");
	define ('BACKUP_PATH',				RESOURCE_PATH . "/backup");
	define ('USERS_PATH',				RESOURCE_PATH . "/users");
	define ('FOTOGR_PATH',				RESOURCE_PATH . "/fotogr");
	define ('VIDEO_PATH',				RESOURCE_PATH . "/video");
	define ('FILE_PATH',				RESOURCE_PATH . "/files");
	define ('SITE_URL',					'http://' . $_SERVER['HTTP_HOST']);

	// Library path
	$path = explode(PATH_SEPARATOR, get_include_path());
	array_unshift($path, BASE_PATH . '/library');
	set_include_path(join(PATH_SEPARATOR, $path));

	// При ошибке в sql_placeholder_ex() возвращается запрос с
	// указанным ниже префиксом.
	define ('PLACEHOLDER_ERROR_PREFIX',	_("SQL_ERROR: "));

	// Параметры для работы управляющих структур (NSTREE, STREE и т.д.)
	define ('TREE_STRUCT_ID',			'id');
	define ('TREE_STRUCT_DATA_ID',		'data_id');
	define ('TREE_STRUCT_LEFT',			'lft');
	define ('TREE_STRUCT_RIGHT',		'rgt');
	define ('TREE_STRUCT_LEVEL',		'level');
	define ('TREE_STRUCT_PARENT_ID',	'parent_id');
	define ('TREE_STRUCT_ORD',			'ord');
	define ('TREE_NODE_INDENT',			20);

	// Типы исполнителей
	define ('TE_EXECUTOR_WYSIWYG',		1);
	define ('TE_EXECUTOR_FILE',			2);
	define ('TE_EXECUTOR_CODE',			3);
	define ('TE_EXECUTOR_URL',			4);
	define ('TE_EXECUTOR_SIMPLE',		5);

	// Доступы для страниц / меню по языкам
	define ('ACC_LNG_PAGE',				1);
	define ('ACC_LNG_MENU',				2);

	// Типы визуальной обработки исполнителей
	define ('TE_EXECUTOR_SCREEN_WYSIWYG', 1);
	define ('TE_EXECUTOR_SCREEN_FILE',    2);
	define ('TE_EXECUTOR_SCREEN_CODE',    3);

	$__TYPE_EXECUTOR = array (
		TE_EXECUTOR_WYSIWYG	=> array(
			'text'   => _("WYSIWYG - элемент"),
			'form'   => TE_EXECUTOR_SCREEN_WYSIWYG
		),
		TE_EXECUTOR_FILE => array(
			'text'   => _("Адрес файла"),
			'form'   => TE_EXECUTOR_SCREEN_FILE
		),
		TE_EXECUTOR_CODE => array(
			'text'   => _("Код в базе данных"),
			'form'   => TE_EXECUTOR_SCREEN_CODE
		),
		TE_EXECUTOR_URL => array(
			'text'   => _("URL - адрес"),
			'form'   => TE_EXECUTOR_SCREEN_FILE
		),
		TE_EXECUTOR_SIMPLE => array(
			'text'   => _("Простой текст"),
			'form'   => TE_EXECUTOR_SCREEN_WYSIWYG
		)
	);
	// Падежи и написания месяцев на разных падежах
	define ('IMENIT_PAD',				1);
	define ('RODIT_PAD',				2);

	$__MONTH_NAME = array(
		IMENIT_PAD => array(
			1  => _("январь"),
			2  => _("февраль"),
			3  => _("март"),
			4  => _("апрель"),
			5  => _("май"),
			6  => _("июнь"),
			7  => _("июль"),
			8  => _("август"),
			9  => _("сентябрь"),
			10 => _("октябрь"),
			11 => _("ноябрь"),
			12 => _("декабрь")
		),
		RODIT_PAD => array(
			1  => _("января"),
			2  => _("февраля"),
			3  => _("марта"),
			4  => _("апреля"),
			5  => _("мая"),
			6  => _("июня"),
			7  => _("июля"),
			8  => _("августа"),
			9  => _("сентября"),
			10 => _("октября"),
			11 => _("ноября"),
			12 => _("декабря")
		)
	);

	$__WEEK_NAME = array(
		IMENIT_PAD => array(
			1 => _("понедельник"),
			2 => _("вторник"),
			3 => _("среда"),
			4 => _("четверг"),
			5 => _("пятница"),
			6 => _("суббота"),
			7 => _("воскресенье")
		),
		RODIT_PAD => array(
			1 => _("понедельника"),
			2 => _("вторника"),
			3 => _("среды"),
			4 => _("четверга"),
			5 => _("пятницы"),
			6 => _("субботы"),
			7 => _("воскресенья")
		)
	);

	// Варианты признака печати
	define('TE_PRINT_NOTHING',   		1);
	define('TE_PRINT_HTML',      		2);
	define('TE_PRINT_PRINTABLE', 		3);
	define('TE_PRINT_ALL',       		4);

	$__TYPE_PRINT = array (
		TE_PRINT_NOTHING				=> _("Никогда не отображается"),
		TE_PRINT_HTML					=> _("Отображается только на странице"),
		TE_PRINT_PRINTABLE				=> _("Выводится только при печати"),
		TE_PRINT_ALL					=> _("Отображается и на странице и при печати")
	);
	// Варианты обработки контентных модулей при отсутствии контента на данном языке
	define('NODE_WILE_UP',				1);
	define('NODE_WILE_CONSTRUCT',		2);
	define('NODE_WILE_SIMPLE',			3);
	define('NODE_WILE_404',				4);

	$__TYPE_WILE = array (
		NODE_WILE_UP					=> _("Переадресация на уровень выше"),
		NODE_WILE_CONSTRUCT				=> _("Выдача сообщения"),
		NODE_WILE_SIMPLE				=> _("Построение того что есть"),
		NODE_WILE_404					=> _("Переадресация на страницу ошибки 404")
	);

	$dictionaryCategory = array(
		array(
			'id' => 1,
			'name' => 'средней важности'
		),
		array(
			'id' => 2,
			'name' => 'важная'
		),
		array(
			'id' => 3,
			'name' => 'актуальное или горячее предложение'
		)
	);

	define('NAVIGATION_MODULE',			"modules");
	define('NAVIGATION_DICT' ,			"dicts");

	// Массив ID и путей к картинкам флагов стран
	$__COUNTRY_FLAGS = array(
		1 => array( 'path' => "/virab/images/flags/a/Afghanistan.gif" ),
		2 => array( 'path' => "/virab/images/flags/a/African Union.gif" ),
		3 => array( 'path' => "/virab/images/flags/a/Albania.gif" ),
		4 => array( 'path' => "/virab/images/flags/a/Algeria.gif" ),
		5 => array( 'path' => "/virab/images/flags/a/American Samoa.gif" ),
		6 => array( 'path' => "/virab/images/flags/a/Andorra.gif" ),
		7 => array( 'path' => "/virab/images/flags/a/Angola.gif" ),
		8 => array( 'path' => "/virab/images/flags/a/Anguilla.gif" ),
		9 => array( 'path' => "/virab/images/flags/a/Antarctica.gif" ),
		10 => array( 'path' => "/virab/images/flags/a/Antigua & Barbuda.gif" ),
		11 => array( 'path' => "/virab/images/flags/a/Arab League.gif" ),
		12 => array( 'path' => "/virab/images/flags/a/Argentina.gif" ),
		13 => array( 'path' => "/virab/images/flags/a/Armenia.gif" ),
		14 => array( 'path' => "/virab/images/flags/a/Aruba.gif" ),
		15 => array( 'path' => "/virab/images/flags/a/ASEAN.gif" ),
		16 => array( 'path' => "/virab/images/flags/a/Australia.gif" ),
		17 => array( 'path' => "/virab/images/flags/a/Austria.gif" ),
		18 => array( 'path' => "/virab/images/flags/a/Azerbaijan.gif" ),
		19 => array( 'path' => "/virab/images/flags/b/Bahamas.gif" ),
		20 => array( 'path' => "/virab/images/flags/b/Bahrain.gif" ),
		21 => array( 'path' => "/virab/images/flags/b/Bangladesh.gif" ),
		22 => array( 'path' => "/virab/images/flags/b/Barbados.gif" ),
		23 => array( 'path' => "/virab/images/flags/b/Belarus.gif" ),
		24 => array( 'path' => "/virab/images/flags/b/Belgium.gif" ),
		25 => array( 'path' => "/virab/images/flags/b/Belize.gif" ),
		26 => array( 'path' => "/virab/images/flags/b/Benin.gif" ),
		27 => array( 'path' => "/virab/images/flags/b/Bermuda.gif" ),
		28 => array( 'path' => "/virab/images/flags/b/Bhutan.gif" ),
		29 => array( 'path' => "/virab/images/flags/b/Bolivia.gif" ),
		30 => array( 'path' => "/virab/images/flags/b/Bosnia & Herzegovina.gif" ),
		31 => array( 'path' => "/virab/images/flags/b/Botswana.gif" ),
		32 => array( 'path' => "/virab/images/flags/b/Brazil.gif" ),
		33 => array( 'path' => "/virab/images/flags/b/Brunei.gif" ),
		34 => array( 'path' => "/virab/images/flags/b/Bulgaria.gif" ),
		35 => array( 'path' => "/virab/images/flags/b/Burkina Faso.gif" ),
		36 => array( 'path' => "/virab/images/flags/b/Burundi.gif" ),
		37 => array( 'path' => "/virab/images/flags/c/Cambodja.gif" ),
		38 => array( 'path' => "/virab/images/flags/c/Cameroon.gif" ),
		39 => array( 'path' => "/virab/images/flags/c/Canada.gif" ),
		40 => array( 'path' => "/virab/images/flags/c/Cape Verde.gif" ),
		41 => array( 'path' => "/virab/images/flags/c/CARICOM.gif" ),
		42 => array( 'path' => "/virab/images/flags/c/Cayman Islands.gif" ),
		43 => array( 'path' => "/virab/images/flags/c/Central African Republic.gif" ),
		44 => array( 'path' => "/virab/images/flags/c/Chad.gif" ),
		45 => array( 'path' => "/virab/images/flags/c/Chile.gif" ),
		46 => array( 'path' => "/virab/images/flags/c/China.gif" ),
		47 => array( 'path' => "/virab/images/flags/c/CIS.gif" ),
		48 => array( 'path' => "/virab/images/flags/c/Colombia.gif" ),
		49 => array( 'path' => "/virab/images/flags/c/Commonwealth.gif" ),
		50 => array( 'path' => "/virab/images/flags/c/Comoros.gif" ),
		51 => array( 'path' => "/virab/images/flags/c/Congo-Brazzaville.gif" ),
		52 => array( 'path' => "/virab/images/flags/c/Congo-Kinshasa(Zaire).gif" ),
		53 => array( 'path' => "/virab/images/flags/c/Cook Islands.gif" ),
		54 => array( 'path' => "/virab/images/flags/c/Costa Rica.gif" ),
		55 => array( 'path' => "/virab/images/flags/c/Cote d'Ivoire.gif" ),
		56 => array( 'path' => "/virab/images/flags/c/Croatia.gif" ),
		57 => array( 'path' => "/virab/images/flags/c/Cuba.gif" ),
		58 => array( 'path' => "/virab/images/flags/c/Cyprus.gif" ),
		59 => array( 'path' => "/virab/images/flags/c/Czech Republic.gif" ),
		60 => array( 'path' => "/virab/images/flags/d/Denmark.gif" ),
		61 => array( 'path' => "/virab/images/flags/d/Djibouti.gif" ),
		62 => array( 'path' => "/virab/images/flags/d/Dominica.gif" ),
		63 => array( 'path' => "/virab/images/flags/d/Dominican Republic.gif" ),
		64 => array( 'path' => "/virab/images/flags/e/East Timor.gif" ),
		65 => array( 'path' => "/virab/images/flags/e/Ecuador.gif" ),
		66 => array( 'path' => "/virab/images/flags/e/Egypt.gif" ),
		67 => array( 'path' => "/virab/images/flags/e/El Salvador.gif" ),
		68 => array( 'path' => "/virab/images/flags/e/England.gif" ),
		69 => array( 'path' => "/virab/images/flags/e/Equatorial Guinea.gif" ),
		70 => array( 'path' => "/virab/images/flags/e/Eritrea.gif" ),
		71 => array( 'path' => "/virab/images/flags/e/Estonia.gif" ),
		72 => array( 'path' => "/virab/images/flags/e/Ethiopia.gif" ),
		73 => array( 'path' => "/virab/images/flags/e/European Union.gif" ),
		74 => array( 'path' => "/virab/images/flags/f/Faroes.gif" ),
		75 => array( 'path' => "/virab/images/flags/f/Fiji.gif" ),
		76 => array( 'path' => "/virab/images/flags/f/Finland.gif" ),
		77 => array( 'path' => "/virab/images/flags/f/France.gif" ),
		78 => array( 'path' => "/virab/images/flags/g/Gabon.gif" ),
		79 => array( 'path' => "/virab/images/flags/g/Gambia.gif" ),
		80 => array( 'path' => "/virab/images/flags/g/Georgia.gif" ),
		81 => array( 'path' => "/virab/images/flags/g/Germany.gif" ),
		82 => array( 'path' => "/virab/images/flags/g/Ghana.gif" ),
		83 => array( 'path' => "/virab/images/flags/g/Gibraltar.gif" ),
		84 => array( 'path' => "/virab/images/flags/g/Greece.gif" ),
		85 => array( 'path' => "/virab/images/flags/g/Greenland.gif" ),
		86 => array( 'path' => "/virab/images/flags/g/Grenada.gif" ),
		87 => array( 'path' => "/virab/images/flags/g/Guadeloupe.gif" ),
		88 => array( 'path' => "/virab/images/flags/g/Guademala.gif" ),
		89 => array( 'path' => "/virab/images/flags/g/Guam.gif" ),
		90 => array( 'path' => "/virab/images/flags/g/Guinea-Bissau.gif" ),
		91 => array( 'path' => "/virab/images/flags/g/Guinea.gif" ),
		92 => array( 'path' => "/virab/images/flags/g/Guyana.gif" ),
		93 => array( 'path' => "/virab/images/flags/h/Haiti.gif" ),
		94 => array( 'path' => "/virab/images/flags/h/Honduras.gif" ),
		95 => array( 'path' => "/virab/images/flags/h/Hong Kong.gif" ),
		96 => array( 'path' => "/virab/images/flags/h/Hungary.gif" ),
		97 => array( 'path' => "/virab/images/flags/i/Iceland.gif" ),
		98 => array( 'path' => "/virab/images/flags/i/India.gif" ),
		99 => array( 'path' => "/virab/images/flags/i/Indonesia.gif" ),
		100 => array( 'path' => "/virab/images/flags/i/Iran.gif" ),
		101 => array( 'path' => "/virab/images/flags/i/Iraq.gif" ),
		102 => array( 'path' => "/virab/images/flags/i/Ireland.gif" ),
		103 => array( 'path' => "/virab/images/flags/i/Islamic Conference.gif" ),
		104 => array( 'path' => "/virab/images/flags/i/Israel.gif" ),
		105 => array( 'path' => "/virab/images/flags/i/Italy.gif" ),
		106 => array( 'path' => "/virab/images/flags/j/Jamaica.gif" ),
		107 => array( 'path' => "/virab/images/flags/j/Japan.gif" ),
		108 => array( 'path' => "/virab/images/flags/j/Jersey.gif" ),
		109 => array( 'path' => "/virab/images/flags/j/Jordan.gif" ),
		110 => array( 'path' => "/virab/images/flags/k/Kazakhstan.gif" ),
		111 => array( 'path' => "/virab/images/flags/k/Kenya.gif" ),
		112 => array( 'path' => "/virab/images/flags/k/Kiribati.gif" ),
		113 => array( 'path' => "/virab/images/flags/k/Kuwait.gif" ),
		114 => array( 'path' => "/virab/images/flags/k/Kyrgyzstan.gif" ),
		115 => array( 'path' => "/virab/images/flags/l/Laos.gif" ),
		116 => array( 'path' => "/virab/images/flags/l/Latvia.gif" ),
		117 => array( 'path' => "/virab/images/flags/l/Lebanon.gif" ),
		118 => array( 'path' => "/virab/images/flags/l/Lesotho.gif" ),
		119 => array( 'path' => "/virab/images/flags/l/Liberia.gif" ),
		120 => array( 'path' => "/virab/images/flags/l/Libya.gif" ),
		121 => array( 'path' => "/virab/images/flags/l/Liechtenstein.gif" ),
		122 => array( 'path' => "/virab/images/flags/l/Lithuania.gif" ),
		123 => array( 'path' => "/virab/images/flags/l/Luxembourg.gif" ),
		124 => array( 'path' => "/virab/images/flags/m/Macao.gif" ),
		125 => array( 'path' => "/virab/images/flags/m/Macedonia.gif" ),
		126 => array( 'path' => "/virab/images/flags/m/Madagascar.gif" ),
		127 => array( 'path' => "/virab/images/flags/m/Malawi.gif" ),
		128 => array( 'path' => "/virab/images/flags/m/Malaysia.gif" ),
		129 => array( 'path' => "/virab/images/flags/m/Maldives.gif" ),
		130 => array( 'path' => "/virab/images/flags/m/Mali.gif" ),
		131 => array( 'path' => "/virab/images/flags/m/Malta.gif" ),
		132 => array( 'path' => "/virab/images/flags/m/Marshall Islands.gif" ),
		133 => array( 'path' => "/virab/images/flags/m/Martinique.gif" ),
		134 => array( 'path' => "/virab/images/flags/m/Mauritania.gif" ),
		135 => array( 'path' => "/virab/images/flags/m/Mauritius.gif" ),
		136 => array( 'path' => "/virab/images/flags/m/Mexico.gif" ),
		137 => array( 'path' => "/virab/images/flags/m/Micronesia.gif" ),
		137 => array( 'path' => "/virab/images/flags/m/Moldova.gif" ),
		139 => array( 'path' => "/virab/images/flags/m/Monaco.gif" ),
		140 => array( 'path' => "/virab/images/flags/m/Mongolia.gif" ),
		141 => array( 'path' => "/virab/images/flags/m/Montenegro.gif" ),
		142 => array( 'path' => "/virab/images/flags/m/Montserrat.gif" ),
		143 => array( 'path' => "/virab/images/flags/m/Morocco.gif" ),
		144 => array( 'path' => "/virab/images/flags/m/Mozambique.gif" ),
		145 => array( 'path' => "/virab/images/flags/m/Myanmar(Burma).gif" ),
		146 => array( 'path' => "/virab/images/flags/n/Namibia.gif" ),
		147 => array( 'path' => "/virab/images/flags/n/NATO.gif" ),
		148 => array( 'path' => "/virab/images/flags/n/Nauru.gif" ),
		149 => array( 'path' => "/virab/images/flags/n/Nepal.gif" ),
		150 => array( 'path' => "/virab/images/flags/n/Netherlands Antilles.gif" ),
		151 => array( 'path' => "/virab/images/flags/n/Netherlands.gif" ),
		152 => array( 'path' => "/virab/images/flags/n/New Zealand.gif" ),
		153 => array( 'path' => "/virab/images/flags/n/Nicaragua.gif" ),
		154 => array( 'path' => "/virab/images/flags/n/Niger.gif" ),
		155 => array( 'path' => "/virab/images/flags/n/Nigeria.gif" ),
		156 => array( 'path' => "/virab/images/flags/n/North Korea.gif" ),
		157 => array( 'path' => "/virab/images/flags/n/Northern Cyprus.gif" ),
		158 => array( 'path' => "/virab/images/flags/n/Northern Ireland.gif" ),
		159 => array( 'path' => "/virab/images/flags/n/Norway.gif" ),
		160 => array( 'path' => "/virab/images/flags/o/Olimpic Movement.gif" ),
		161 => array( 'path' => "/virab/images/flags/o/Oman.gif" ),
		162 => array( 'path' => "/virab/images/flags/o/OPEC.gif" ),
		163 => array( 'path' => "/virab/images/flags/p/Pakistan.gif" ),
		164 => array( 'path' => "/virab/images/flags/p/Palau.gif" ),
		165 => array( 'path' => "/virab/images/flags/p/Palestine.gif" ),
		166 => array( 'path' => "/virab/images/flags/p/Panama.gif" ),
		167 => array( 'path' => "/virab/images/flags/p/Papua New Guinea.gif" ),
		168 => array( 'path' => "/virab/images/flags/p/Paraguay.gif" ),
		169 => array( 'path' => "/virab/images/flags/p/Peru.gif" ),
		170 => array( 'path' => "/virab/images/flags/p/Philippines.gif" ),
		171 => array( 'path' => "/virab/images/flags/p/Poland.gif" ),
		172 => array( 'path' => "/virab/images/flags/p/Portugal.gif" ),
		173 => array( 'path' => "/virab/images/flags/p/Puerto Rico.gif" ),
		174 => array( 'path' => "/virab/images/flags/q/Qatar.gif" ),
		175 => array( 'path' => "/virab/images/flags/r/Red Cross.gif" ),
		176 => array( 'path' => "/virab/images/flags/r/Reunion.gif" ),
		177 => array( 'path' => "/virab/images/flags/r/Romania.gif" ),
		178 => array( 'path' => "/virab/images/flags/r/Russian Federation.gif" ),
		179 => array( 'path' => "/virab/images/flags/r/Rwanda.gif" ),
		180 => array( 'path' => "/virab/images/flags/s/Saint Lucia.gif" ),
		181 => array( 'path' => "/virab/images/flags/s/Samoa.gif" ),
		182 => array( 'path' => "/virab/images/flags/s/San Marino.gif" ),
		183 => array( 'path' => "/virab/images/flags/s/Sao Tome & Principe.gif" ),
		184 => array( 'path' => "/virab/images/flags/s/Saudi Arabia.gif" ),
		185 => array( 'path' => "/virab/images/flags/s/Scotland.gif" ),
		186 => array( 'path' => "/virab/images/flags/s/Senegal.gif" ),
		187 => array( 'path' => "/virab/images/flags/s/Serbia.gif" ),
		188 => array( 'path' => "/virab/images/flags/s/Seyshelles.gif" ),
		189 => array( 'path' => "/virab/images/flags/s/Sierra Leone.gif" ),
		190 => array( 'path' => "/virab/images/flags/s/Singapore.gif" ),
		191 => array( 'path' => "/virab/images/flags/s/Slovakia.gif" ),
		192 => array( 'path' => "/virab/images/flags/s/Slovenia.gif" ),
		193 => array( 'path' => "/virab/images/flags/s/Solomon Islands.gif" ),
		194 => array( 'path' => "/virab/images/flags/s/Somalia.gif" ),
		195 => array( 'path' => "/virab/images/flags/s/Somaliland.gif" ),
		196 => array( 'path' => "/virab/images/flags/s/South Afriica.gif" ),
		197 => array( 'path' => "/virab/images/flags/s/South Korea.gif" ),
		198 => array( 'path' => "/virab/images/flags/s/Spain.gif" ),
		199 => array( 'path' => "/virab/images/flags/s/Sri Lanka.gif" ),
		200 => array( 'path' => "/virab/images/flags/s/St Kitts & Nevis.gif" ),
		201 => array( 'path' => "/virab/images/flags/s/St Vincent & the Grenadines.gif" ),
		202 => array( 'path' => "/virab/images/flags/s/Sudan.gif" ),
		203 => array( 'path' => "/virab/images/flags/s/Suriname.gif" ),
		204 => array( 'path' => "/virab/images/flags/s/Swaziland.gif" ),
		205 => array( 'path' => "/virab/images/flags/s/Sweden.gif" ),
		206 => array( 'path' => "/virab/images/flags/s/Switzerland.gif" ),
		207 => array( 'path' => "/virab/images/flags/s/Syria.gif" ),
		208 => array( 'path' => "/virab/images/flags/t/Tahiti(French Polinesia).gif" ),
		209 => array( 'path' => "/virab/images/flags/t/Taiwan.gif" ),
		210 => array( 'path' => "/virab/images/flags/t/Tajikistan.gif" ),
		211 => array( 'path' => "/virab/images/flags/t/Tanzania.gif" ),
		212 => array( 'path' => "/virab/images/flags/t/Thailand.gif" ),
		213 => array( 'path' => "/virab/images/flags/t/Timor-Leste.gif" ),
		214 => array( 'path' => "/virab/images/flags/t/Togo.gif" ),
		215 => array( 'path' => "/virab/images/flags/t/Tonga.gif" ),
		216 => array( 'path' => "/virab/images/flags/t/Trinidad & Tobago.gif" ),
		217 => array( 'path' => "/virab/images/flags/t/Tunisia.gif" ),
		218 => array( 'path' => "/virab/images/flags/t/Turkey.gif" ),
		219 => array( 'path' => "/virab/images/flags/t/Turkmenistan.gif" ),
		220 => array( 'path' => "/virab/images/flags/t/Turks and Caicos Islands.gif" ),
		221 => array( 'path' => "/virab/images/flags/t/Tuvalu.gif" ),
		222 => array( 'path' => "/virab/images/flags/u/Uganda.gif" ),
		223 => array( 'path' => "/virab/images/flags/u/Ukraine.gif" ),
		224 => array( 'path' => "/virab/images/flags/u/United Arab Emirates.gif" ),
		225 => array( 'path' => "/virab/images/flags/u/United Kingdom(Great Britain).gif" ),
		226 => array( 'path' => "/virab/images/flags/u/United Nations.gif" ),
		227 => array( 'path' => "/virab/images/flags/u/United States of America.gif" ),
		228 => array( 'path' => "/virab/images/flags/u/Uruguay.gif" ),
		229 => array( 'path' => "/virab/images/flags/u/Uzbekistan.gif" ),
		230 => array( 'path' => "/virab/images/flags/v/Vanutau.gif" ),
		231 => array( 'path' => "/virab/images/flags/v/Vatican City.gif" ),
		232 => array( 'path' => "/virab/images/flags/v/Venezuela.gif" ),
		233 => array( 'path' => "/virab/images/flags/v/Viet Nam.gif" ),
		234 => array( 'path' => "/virab/images/flags/v/Virgin Islands British.gif" ),
		235 => array( 'path' => "/virab/images/flags/v/Virgin Islands US.gif" ),
		236 => array( 'path' => "/virab/images/flags/w/Wales.gif" ),
		237 => array( 'path' => "/virab/images/flags/w/Western Sahara.gif" ),
		238 => array( 'path' => "/virab/images/flags/y/Yemen.gif" ),
		239 => array( 'path' => "/virab/images/flags/z/Zambia.gif" ),
		240 => array( 'path' => "/virab/images/flags/z/Zimbabwe.gif" ),
	);
