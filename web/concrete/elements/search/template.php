<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>

<div data-search-element="wrapper"></div>

<div data-search-element="results">

<table border="0" cellspacing="0" cellpadding="0" class="ccm-search-results-table">
<thead>
</thead>
<tbody>
</tbody>
</table>

<div class="ccm-search-results-pagination"></div>

</div>

<script type="text/template" data-template="search-results-pagination">
<ul class="pagination">
	<li class="<%=pagination.prevClass%>"><%=pagination.previousPage%></li>
	<%=pagination.pages%>
	<li class="<%=pagination.nextClass%>"><%=pagination.nextPage%></li>
</div>
</script>

<script type="text/template" data-template="search-results-table-head">
<tr>
	<th><span class="ccm-search-results-checkbox"><input type="checkbox" data-search-checkbox="select-all" /></span></th>
	<% 
	for (i = 0; i < columns.length; i++) {
		var column = columns[i];
		if (column.isColumnSortable) { %>
			<th class="<%=column.className%>"><a href="<%=column.sortURL%>"><%=column.title%></a></th>
		<% } else { %>
			<th><span><%=column.title%></span></th>
		<% } %>
	<% } %>
</tr>
</script>
