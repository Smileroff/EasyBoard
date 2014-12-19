<br/>
<p>Объявление <b>№[+id+]</b>;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Автор <input name="createdby" style="width:120px;" class="styler" value="[+createdby+]" size="10" type="number"></input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
просмотры:
<input class="styler" type="text" name="hit" style="width:120px;" value="[+hit+]" />
<label><input name="published" type="checkbox" [+published+]/> Опубликовано</label>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<table border="0"><tr>
<td valign="top">
	<div class="sec maxheight">
		<p>Рубрика:</p>
		<select name="parent">
			[+parent+]				
		</select>
	</div>
</td>
<td valign="top">
	<div class="sec maxheight">
		<p>Город:</p>
		<select name="city" data-search="true">
			[+city+]				
		</select>
	</div>
	<label><input name="allcity" type="checkbox" [+allcity+]/> Публиковать во всех городах</label>
</td>
</tr></table>
<br/><p>Заголовок:</p>
<input class="styler" type="text" name="pagetitle" style="width:90%;" value="[+pagetitle+]" />
<br/><br/><p>Текст объявления:</p>
<textarea name="content" class="styler" style="width:90%;height:190px;" placeholder="Текст объявления">[+content+]</textarea>
<br/><br/><p>Контакты:</p>
<input class="styler" type="text" name="contact" style="width:90%;" value="[+contact+]" />
<br/><br/><p>Цена, если есть:</p>
<input class="styler" type="text" name="price" style="width:90%;" value="[+price+]" />
<br/><br/>
[+image+]
<p>&nbsp;</p>