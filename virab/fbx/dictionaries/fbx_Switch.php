<?
    switch($Fusebox["fuseaction"]) {
     case "main":
     case "Fusebox.defaultFuseaction":
       include ("dsp_Main.php");
     break;
     
     default:
      print _("Для  fuseaction <b>'" . $Fusebox["fuseaction"] . "'</b> не зарегистрирован обработчик!");
      break;
    }
?>