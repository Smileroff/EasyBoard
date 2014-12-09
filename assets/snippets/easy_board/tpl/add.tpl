<br/>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="act" value="add">
<p>Автор: [+username+]
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
<br/><p>Заголовок:</p>
<input type="text" name="pagetitle" style="width:90%;" value="" />
<br/><br/><p>Текст объявления:</p>
<textarea name="content" style="width:90%;height:190px;" ></textarea>
<br/><br/><p>Телефон:</p>
<input type="text" name="contact" style="width:90%;" value="" />
<br/><br/><p>Цена, если есть:</p>
<input type="text" name="price" style="width:90%;" value="" />
<br/><br/>
[+image+]
<p>&nbsp;</p>
<input type="submit" value="Создать и опубликовать">
</form>
<p>&nbsp;</p>