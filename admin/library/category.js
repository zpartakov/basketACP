// JavaScript Document
function checkCategoryForm()
{
    with (window.document.frmCategory) {
		if (isEmpty(txtName, 'Enter category name')) {
			return;
		} else if (isEmpty(mtxDescription, 'Entrer la description de la catégorie')) {
			return;
		} else {
			submit();
		}
	}
}

function addCategory(parentId)
{
	targetUrl = 'index.php?view=add';
	if (parentId != 0) {
		targetUrl += '&parentId=' + parentId;
	}
	
	window.location.href = targetUrl;
}

function modifyCategory(catId)
{
	window.location.href = 'index.php?view=modify&catId=' + catId;
}

function deleteCategory(catId)
{
	if (confirm('Effacer la catégorie va aussi supprimer tous les produits qu\'elle contient\nContinuer tout de même?')) {
		window.location.href = 'processCategory.php?action=delete&catId=' + catId;
	}
}

function deleteImage(catId)
{
	if (confirm('Effacer cette image?')) {
		window.location.href = 'processCategory.php?action=deleteImage&catId=' + catId;
	}
}

function disponibilite(catId) {
	cat=window.document.getElementById("txtName").value;
	if(window.document.getElementById("dispo").checked) {
		catn=cat.replace('<!-- nondisp -->','');
		window.document.getElementById("txtName").value=catn;
	}else{
		catn='<!-- nondisp -->'+cat;
		window.document.getElementById("txtName").value=catn;
}	
}
