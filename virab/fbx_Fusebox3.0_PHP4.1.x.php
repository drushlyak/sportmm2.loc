<?php
/*
This file is broken into twelve sections.
Section Zero (PHP-specific) requires the listFunctions.php library and the SaveContent class
Section One is the Fusedoc for the file. (PHP note: )
Section Two contains the public "API-style" variables that are used in the Fusebox framework.
Section Three establishes the private structure "fb_".
Section Four is the former formURL2attributes Custom Tag.
Section Five includes the fbx_circuits.php file.
Section Six creates a mirror of the fusebox.circuits strucure for reverse look-ups.
Section Seven includes the root fbx_settings.php file.
Section Eight massages attributes.fuseaction and begins the aliased lookup process.
Section Nine includes nested fbx_settings.php files top-to-bottom.
Section Ten includes the target circuit fbx_switch file, which processes the requested fuseaction.
Section Eleven includes any layout files, in bottom-to-top order to allow layouts to be nested. It also outputs the final display of the page.
*/

/*
*********************SECTION ZERO*********************
List Functions are used extensively in CF-Fusebox, and so to ease the transition to PHP, a recreation of the List Functions written in PHP has been added (fbx_ListFunctions.php).  Similarly, <CFSaveContent> is a new tag in ColdFusion 5.0, recreating the functionality of Steve Nelson's <cf_BodyContent> custom tag.  Here it is recreated in PHP as a class, and it includes some new functionality specific to PHP.  Also included in fbx_SaveContent.php is a Location() function recreating the functionality of <CFLocation>.
*/
require_once("fbx_ListFunctions.php");
require_once("fbx_SaveContent.php");

/*
*********************SECTION ONE*********************
For more information about Fusedocs and how to read them, visit fusebox.org and halhelms.com.

<fusedoc fuse="fbx_Fusebox3.0_PHP4.0.6.php">
	<responsibilities>
		This is the PHP version.
		I am the code behind Fusebox 3.0 that handles nesting, layouts--oh, a bunch of stuff, really. PLEASE BE VERY CAREFUL ABOUT MAKING ANY CHANGES TO THIS FILE, AS IT WILL RENDER IT NON-COMPLIANT WITH THE STANDARD NOTED ABOVE. There is no need to modify this file for any settings. All settings can occur in fbx_Settings.php.
	</responsibilities>
	<properties>
		<property name="version" value="3.0" />
		<property name="build" value="2c" />
		<history author="John Quarto-vonTivadar" date="27 Sep 2001" email="jcq@mindspring.com">Portions of code contributed by Steve Nelson, Hal Helms, Jeff Peters, Nat Papovich, Patrick McElhaney, Fred Sanders and Stan Cox.</history>
		<history author="Nat Papovich" date="01 Oct 2001" email="mcnattyp@yahoo.com" type="Update" />
		<history author="David Huyck" date="07 Oct 2001" email="b@bombusbee.com" type="Update">I translated this impressive chunk of code from ColdFusion to PHP.</history>
		<history author="David Huyck" date="23 Oct 2001" email="b@bombusbee.com" type="Update">I cleaned up some case-sensitivity issues and set a new default for the $Fusebox["baseHref"] var, required for SES URLs.</history>
		<history author="David Huyck" date="7 Nov 2001" email="b@bombusbee.com" type="Update">Back track-- I removed the $Fusebox["baseHref"] var.  Only required if using SES URLs, so the developer should insert it into their own layout template. Also instated the $Fusebox["isCustomTag"] variable, as I have added a "Module" class as an extension of the SaveContent class.</history>
		<history author="David Huyck" date="25 March 2002" email="b@bombusbee.com" type="Update">Repaired some XML in this Fusedoc.</history>
		<history author="David Huyck" date="25 March 2002" email="b@bombusbee.com" type="Update">Created this file for PHP versions 4.1.0 and up, using the new superglobal vars for GET and POST in the $attributes array merge.</history>
	</properties>
	<io>
		<out>
			<array name="$Fusebox">
				<boolean name="isCustomTag" default="FALSE" />
				<boolean name="isHomeCircuit" default="FALSE" />
				<boolean name="isTargetCircuit" default="FALSE" />
				<string name="fuseaction" default="" comments="will be assigned a literal value of 'fusebox.defaultfuseaction' if attributes.fuseaction comes in as 'circuit.' with no fuseaction passed." />
				<string name="circuit" default="" />
				<string name="homeCircuit" default="" />
				<string name="targetCircuit" default="" />
				<string name="thisCircuit" default="" />
				<string name="thisLayoutPath" default="" />
				<boolean name="suppressErrors" default="FALSE" />
				<string name="currentPath" default="" />
				<string name="rootPath" default="" />
			</array>
			<array name="$FB_" comments="Internal use only. Please treat the FB_ as a reserved structure, not to be touched.">
			</array>
		</out>
	</io>
</fusedoc> 
*/

/*
*********************SECTION TWO*********************
The $Fusebox associative array below is a array encompassing the public Fusebox API. We recommend making no changes to this array as it will render your application non-compliant to the Fusebox 3.0 standard.

	$Fusebox["isCustomTag"]: (Not relevent to PHP)
	$Fusebox["isHomeCircuit"]:
This boolean variable is set and re-set as the Fusebox framework does its business pulling in fbx_Settings.php files and the fbx_Switch.php file and fbx_Layouts.php files (and associated layout files). It is TRUE only when the currently accessed circuit is the home circuit running this application.
	$Fusebox["isTargetCircuit"]:
Like isHomeCircuit above, this boolean variable is used during the process of cfincluding files. It is TRUE only when the currently accessed circuit is the target circuit running specified by circuit.fuseaction.
	$Fusebox["circuit"]:
This is the first part of the compound fuseaction that gets passed as $attributes["fuseaction"].
	$Fusebox["fuseaction"]:
This is the second part of a compound fuseaction that gets passed as $attributes["fuseaction"]. $Fusebox["fuseaction"] is the variable expression evaluated in fbx_Switch.php.
	$Fusebox["homeCircuit"]:
This variable is set to the root-level circuit as defined in $Fusebox["circuits"] strucure.
	$Fusebox["targetCircuit"]:
This is the circuit the requested fuseaction is to run in. The difference between this variable and $Fusebox["circuit"] above, is that this variable is the circuit alias that was found in the fusebox.circuits file as opposed to the circuit that is being attempted to be found. In all non-error situations $Fusebox["targetCircuit"] and $Fusebox["circuit"] will be the same.
	$Fusebox["thisCircuit"]:
Like IsTargetCircuit and IsHomeCircuit above, this variable is set and re-set during the process of running the fbx_settings.php files and the fbx_Switch.php file, and refers to the circuit alias of the circuit from which files are currently being accessed.
	$Fusebox["thisLayoutPath"]:
This is the directory path that the layout file being used is called from. This variable changes as the layouts are nested one inside another, building the overall page layout.
	$Fusebox["suppressErrors"]:
A boolean variable, which defaults to FALSE. If TRUE, the Fusebox framework will attempt to give you "smarter" errors that may occur from within its own code as it applies to the Framework itself. If FALSE (default), you will receive the native CF error messages. During development you may want to turn this to TRUE and FALSE alternately to ensure you've got your framework set up properly. Set this to TRUE in a production enviroment, since at that point errors that occur will not be Fusebox framework errors but rahter erros in your fuseactions and fuses.	
	$Fusebox["circuits"]:
This variable is a structure whose aliases are the circuit names created in fbx_Circuits.php and whose values are the directory paths to those circuits.
	$Fusebox["currentPath"]:
This variable takes you from the root circuit to any location it is called. If you use images in directories beneath individual circuits, this variable will point to that circuit like "directory/directory/".
	$Fusebox["rootPath"]:
This variable takes you from the circuit it is being called from, back to the root. This is helpful to determine your location relative to the root application.
*/

$Fusebox = array();
if(!isset($isModule)) { $isModule = false; }
$Fusebox["isCustomTag"] = ($isModule)?true:false;
$Fusebox["isHomeCircuit"] = false;
$Fusebox["isTargetCircuit"] = false;
$Fusebox["fuseaction"] = "";
$Fusebox["circuit"] = "";
$Fusebox["homeCircuit"] = "";
$Fusebox["targetCircuit"] = "";
$Fusebox["thisCircuit"] = "";
$Fusebox["thisLayoutPath"] = "";
$Fusebox["suppressErrors"] = false;
$Fusebox["circuits"] = array();
$Fusebox["currentPath"] = "";
$Fusebox["rootPath"] = "";

/*
*********************SECTION THREE*********************
FB_ is an associative array encompassing "private" variables used by the underlying Fusebox framework. Make no changes to it without a full understanding of the ramifications of those changes. 
*/
$FB_ = array();

/*
*********************SECTION FOUR*********************
This code used started as a CF Custom Tag called formURL2attributes.php. It copies all incoming POST and GET variables to an associative array called $attributes.
*/
if(!isset($attributes) || !is_array($attributes)) {
	$attributes = array();
	$attributes = array_merge($_POST, $_GET); // GET overwrites POST
}

/*
*********************SECTION FIVE*********************
Attempt to include the fbx_Circuits.php file, which should be in the same directory as this fbx_Fusebox3.0_PHP4.0.6.php file.
*/
if(!file_exists("fbx_Circuits.php")) {
	if($Fusebox["suppressErrors"]) {
		print 'The Fusebox framework could not find the file: "<b>fbx_Circuits.php</b>"';
		exit;
	}
}
include("fbx_Circuits.php");

/*
*********************SECTION SIX*********************
Create a reverse path look-up of the $Fusebox["circuits"] array which can be used later to conveniently look up the circuit alias of whichever circuit is being accessed at that moment, particularly when determining $Fusebox["thisCircuit"]. 
*/
if(!isset($Fusebox["circuits"]) || !is_array($Fusebox["circuits"])) {
	if($Fusebox["suppressErrors"]) {
		print 'The circuits array does not exist. This must be defined in the Fusebox application\'s root "<b>fbx_Circuits.php</b>" file.';
		exit;
	}
}
$FB_["reverseCircuitPath"] = array();
foreach($Fusebox["circuits"] as $aCircuitName => $aCircuitDefinition) {
	$FB_["reverseCircuitPath"][$aCircuitDefinition] = $aCircuitName;
	if(ListLen($Fusebox["circuits"][$aCircuitName], "/") == 1) {
		$Fusebox["homeCircuit"] = $aCircuitName;
		$Fusebox["isHomeCircuit"] = true;
	}
}

/*
*********************SECTION SEVEN*********************
Attempt to include the fbx_Settings.php file from the home circuit, the same directory that this file is being run from.
*/
if(!file_exists("fbx_Settings.php")) {
	if($Fusebox["suppressErrors"]) {
		print 'The Fusebox framework could not find the file: "<b>fbx_Settings.php</b>"';
		exit;
	}
}
include("fbx_Settings.php");

/*
*********************SECTION EIGHT*********************
Dissect $attributes["fuseaction"] (available in the attributes "scope" either from being converted in the formURL2attributes section or via the above included fbx_Settings.php file in the root directory). If $attributes["fuseaction"] is not a compound fuseaction (i.e. it only includes the circuit in the form of "?fuseaction=circuit."), then set the fuseaction as blank, which the target circuit's default in the case statement will pick up. Now look up the target circuit in the $Fusebox["circuit"] structure for the full path to the circuit.
*/
if(!isset($attributes["fuseaction"])) {
	if($Fusebox["suppressErrors"]) {
		print 'The variable "<b>$attributes["fuseaction"]</b>" is not available.';
		exit;
	}
}
$FB_["rawFA"] = $attributes["fuseaction"];
if(ListLen($FB_["rawFA"], ".") == 1 && substr($FB_["rawFA"], -1) == ".") {
	$Fusebox["fuseaction"] = "Fusebox.defaultFuseaction";
} else {
	$Fusebox["fuseaction"] = ListGetAt($FB_["rawFA"], 2, ".");
}
$Fusebox["circuit"] = ListGetAt($FB_["rawFA"], 1, ".");

if(!isset($Fusebox["circuits"][$Fusebox["circuit"]])) {
	if($Fusebox["suppressErrors"]) {
		print 'The Fusebox framework could not find the circuit you requested: "<b>'.$Fusebox["circuit"].'</b>"';
		exit;
	}
}
$Fusebox["targetCircuit"] = $Fusebox["circuit"];

/*
*********************SECTION NINE*********************
Attempt to include any nested fbx_Settings.php files, in top-to-bottom order so that variables set in children fbx_Settings.php files can overwrite variables set in higher fbx_Settings.php files. To prevent children fbx_Settings.php files from overwriting variables, use if(!isset(...)) rather than an outright variable assignment ($var = "value";). Alternately, any child fbx_Settings.php can set a variable and lower fbx_Settings.cfm files cannot overwrite it unless they set the variable outright. If any fbx_Settings.php file or directory alias cannot be found, continue on.
*/
$FB_["fullPath"] = ListRest($Fusebox["circuits"][$Fusebox["targetCircuit"]], "/"); //make a variable to hold the full path down to the target, excluding the root
$FB_["corePath"] = ""; //initialize
$Fusebox["thisCircuit"] = $Fusebox["homeCircuit"]; //current circuit, set to root now
if(strlen($FB_["fullPath"])) {
	foreach(ListToArray($FB_["fullPath"], "/") as $aPath) {
		$FB_["corePath"] = ListAppend($FB_["corePath"], $aPath, "/"); //add the current circuit with / as delim
		$Fusebox["isHomeCircuit"] = false; //fbx_settings.php files included in this loop are not the home circuit because the home circuit's fbx_Settings is needed much earlier in the process
		$Fusebox["currentPath"] = $FB_["corePath"] . "/";
		if(ListLen($Fusebox["currentPath"], "/") > 0) {
			$Fusebox["rootPath"] = str_repeat("../", ListLen($Fusebox["currentPath"], "/"));
		}
		$FB_["corePath"] = $FB_["corePath"] . "/";
		if(@isset($FB_["reverseCircuitPath"][ $Fusebox["circuits"][ $Fusebox["homeCircuit"] ] . "/" . $FB_["corePath"] ])) {
			$Fusebox["thisCircuit"] = $FB_["reverseCircuitPath"][ $Fusebox["circuits"][ $Fusebox["homeCircuit"] ] . "/" . $FB_["corePath"] ];
			if($Fusebox["thisCircuit"] == $Fusebox["targetCircuit"]) {
				$Fusebox["isTargetCircuit"] = true;
			} else {
				$Fusebox["isTargetCircuit"] = false;
			}
		}
		if(file_exists($FB_["corePath"]."fbx_Settings.php")){
			include($FB_["corePath"]."fbx_Settings.php");
		}
	}
}

/*
*********************SECTION TEN*********************
Now "reach down" and include the fbx_Switch.php in the target circuit, executing $Fusebox["fuseaction"]. Store the contents of the output into a variable called $Fusebox["layout"].
*/
$Fusebox["thisCircuit"] = $Fusebox["targetCircuit"];
$Fusebox["isTargetCircuit"] = true;
$FB_["fuseboxPath"] = $FB_["fullPath"];
if(strlen($FB_["fuseboxPath"])) {
	//if the target circuit is NOT the root circuit
	$FB_["fuseboxPath"] = $FB_["fuseboxPath"] . "/";
	$Fusebox["isHomeCircuit"] = false;
} else {
	$Fusebox["isHomeCircuit"] = true;
}
$Fusebox["currentPath"] = $FB_["fuseboxPath"];
if(ListLen($FB_["fuseboxPath"], "/") > 0) {
	$Fusebox["rootPath"] = str_repeat("../", ListLen($FB_["fuseboxPath"], "/"));
}
if(!file_exists($FB_["fuseboxPath"]."fbx_Switch.php")) {
	if($Fusebox["suppressErrors"]) {
		print 'The Fusebox framework could not find the file "<b>' .$FB_["fuseboxPath"]. 'fbx_Switch.php</b>" file in the circuit you requested: "<b>' .$Fusebox["thisCircuit"]. '</b>"';
		exit;
	}
}
$FB_["SC"] = new SaveContent();
	$FB_["appRootPath"] = getcwd()."/";
	chdir($FB_["appRootPath"].$FB_["fuseboxPath"]);
	include($FB_["appRootPath"].$FB_["fuseboxPath"]."fbx_Switch.php");
	chdir($FB_["appRootPath"]);
$Fusebox["layout"] = $FB_["SC"]->close();


/*
*********************SECTION ELEVEN*********************
Now handle the layouts, resolving them in bottom-to-top order to nest the circuits, if needed. Also set fusebox.thisCircuit equal to the circuit name of the current circuit the code is passing through, which will let any layout files in circuits know where they are. If attempting to include any layout file throws an error, then do nothing and continue on. This ENTIRE section and functionality of nesting layouts and controlling layouts via layout files is optional. If you do not have any layout files (fbx_layout.cfm), or only have a layout file in your root directory, everything will still work.
*/
$FB_["circuitAlias"] = $Fusebox["circuits"][$Fusebox["targetCircuit"]];
$FB_["layoutPath"] = $FB_["circuitAlias"];
while(strlen($FB_["layoutPath"]) > 0) { //loop as long as the layout path has more circuits to run
	if(isset($FB_["reverseCircuitPath"][$FB_["circuitAlias"]])) {
		$Fusebox["thisCircuit"] = $FB_["reverseCircuitPath"][$FB_["circuitAlias"]];
	} else {
		$Fusebox["thisCircuit"] = "";
	}
	if($Fusebox["thisCircuit"] == $Fusebox["targetCircuit"]) {
		$Fusebox["isTargetCircuit"] = true;
	} else {
		$Fusebox["isTargetCircuit"] = false;
	}
	if($Fusebox["thisCircuit"] == $Fusebox["homeCircuit"]) {
		$Fusebox["isHomeCircuit"] = true;
	} else {
		$Fusebox["isHomeCircuit"] = false;
	}
	$Fusebox["thisLayoutPath"] = ListRest($FB_["layoutPath"], "/");
	if(strlen($Fusebox["thisLayoutPath"]) > 0) {
		$Fusebox["thisLayoutPath"] = $Fusebox["thisLayoutPath"] . "/";
	}
	$Fusebox["currentPath"] = $Fusebox["thisLayoutPath"];
	if(ListLen($Fusebox["thisLayoutPath"], "/")) {
		$Fusebox["rootPath"] = str_repeat("../", ListLen($Fusebox["thisLayoutPath"], "/"));
	}
	//include the fbx_Layouts.cfm file for this circuit which decides which layout file to use
	if(file_exists($Fusebox["thisLayoutPath"]."fbx_Layouts.php")) {
		include($Fusebox["thisLayoutPath"]."fbx_Layouts.php");
	} else {
		$Fusebox["layoutFile"] = "";
		$Fusebox["layoutDir"] = "";
	}
	if(strlen($Fusebox["layoutFile"]) > 0) {
		if(!file_exists($Fusebox["thisLayoutPath"].$Fusebox["layoutDir"].$Fusebox["layoutFile"])) {
		//attempt to include the actual layout file which was set by fbx_Layouts.php
			if($Fusebox["suppressErrors"]) {
				print 'The Fusebox framework could not find the layout file "<b>' .$Fusebox["thisLayoutPath"] . $Fusebox["layoutDir"] . $Fusebox["layoutFile"]. '</b>" specified by "<b>' .$Fusebox["thisLayoutPath"]. 'fbx_Layouts.php</b>"';
				exit;
			}
		}
		$FB_["SC"] = new SaveContent();
			include($Fusebox["thisLayoutPath"].$Fusebox["layoutDir"].$Fusebox["layoutFile"]);
		$Fusebox["layout"] = $FB_["SC"]->close();
	}
	//remove one level of the path
	$FB_["layoutPath"] = ListDeleteAt($FB_["layoutPath"], ListLen($FB_["layoutPath"], "/"), "/");
	$FB_["circuitAlias"] = ListDeleteAt($FB_["circuitAlias"], ListLen($FB_["circuitAlias"], "/"), "/");
}
//Finally, output the completely-nested layout
print trim($Fusebox["layout"]);

?>