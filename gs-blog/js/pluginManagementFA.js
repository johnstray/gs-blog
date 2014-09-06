/**
 * @File:     pluginManagementFA.js
 * @Package:  GetSimple Blog [plugin]
 * @Action:   A simple and easy to use blog/newsfeed for GetSimple
 * @Author:   John Stray [https://www.johnstray.id.au/]
 **/

/* Add icon to listing on Plugin Management admin page */
$('#plugins .edittable tr td:first-child b').each(function(i,elem){
  $(elem).closest('tr').addClass(elem.innerHTML.toLowerCase().replace(/ /g, '_').replace(/.php/g, ''));
});