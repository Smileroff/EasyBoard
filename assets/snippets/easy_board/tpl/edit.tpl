<br/>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="act" value="edit">
<input type="hidden" name="ebid" value="[+id+]">
[+notice+]
<p>Объявление <b>№[+id+]</b>;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Автор: [+username+]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<label><input name="published" type="checkbox" [+published+]/> Опубликовано</label>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<table class="eb-table" border="0"><tr>
<td valign="top">
	<div>
		<p>Рубрика:</p>
		<select name="parent">
			[+parentIds+]				
		</select>
	</div>
</td>
<td valign="top">
	<div>
		<p>Город:</p>
		<select name="city">
			[+cityIds+]				
		</select>
	</div>

</td>
</tr></table>
<br/><p>Заголовок:*</p>
<input type="text" name="pagetitle" style="width:90%;" value="[+pagetitle+]" />
<br/><br/><p>Текст объявления:</p>
<textarea name="content" style="width:90%;height:190px;" >[+content+]</textarea>
<br/><br/><p>Телефон:*</p>
<input type="text" name="contact" style="width:90%;" value="[+contact+]" />
<br/><br/><p>Цена, если есть:</p>
<input type="text" name="price" style="width:90%;" value="[+price+]" />
<br/><br/>
[+image+]
<p>&nbsp;</p>
<p>* - обязательные поля</p>
<input type="submit" value="Сохранить">
</form>
<p>&nbsp;</p>