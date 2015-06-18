function show_props(obj, objName) {	
   for (var i in obj) {
	   $.messager.alert(i,obj[i],'error');
	   /*$.messager.show({
           title: 'Error en: '+i,
           msg: obj[i]
       });*/       
   }   
}