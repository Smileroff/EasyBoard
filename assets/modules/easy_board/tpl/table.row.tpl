<tr id="par[+id+]">
    <td>[+id+][+image+]</td>
	<td>[+createdon+]</td>
    <td>[+pagetitle+]</td>
	<td>[+price+]</td>
	<td>[+createdby+]</td>
	<td>[+city+]</td>
	<td>[+parent+]</td>
	<td id="pub[+id+]">[+published+]</td>
    <td align="center" width="112"><button style="margin-top:4px;" class="styler" href="#"onclick="postForm('add','[+id+]');return false;">Редактировать</button></td>
    <td align="center" width="102">
		<ul class="actionButtons">
		  <li id="Button1" style="margin-top:7px;">
			<a href="#" title="Удалить" onclick="ItemAjax('del', '[+id+]', 'par');return false"><img src="media/style/[+theme+]/images/icons/delete.png"> Удалить</a>
		  </li>
		</ul>
	</td>
</tr>