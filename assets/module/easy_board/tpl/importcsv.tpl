<h1>Импорт из csv</h1>
<br/><br/>
<p>Загрузка файла csv. Внимание, операция не обратима. Будьте внимательны, рекомендуется сделать бэкап БД.</p>
<input name="csv" type="file" /><br/>
<label><input name="published" type="checkbox"/> Публиковать, если нет в файле импорта колонки "published"</label><br/><br/>
<button class="styler" href="#"onclick="postForm('importcsvgo', null);return false;">Импорт</button>&nbsp;&nbsp;&nbsp;
<a href="#" onclick="postForm('reload',null);return false;">Отмена</a>