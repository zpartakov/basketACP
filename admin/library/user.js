// JavaScript Document
function checkAddUserForm()
{
	with (window.document.frmAddUser) {
		if (isEmpty(txtUserName, 'Entrer le nom d\'utilisateur')) {
			return;
		} else if (isEmpty(txtPassword, 'Entrer le mot de passe')) {
			return;
		} else {
			submit();
		}
	}
}

function addUser()
{
	window.location.href = 'index.php?view=add';
}

function changePassword(userId)
{
	window.location.href = 'index.php?view=modify&userId=' + userId;
}

function deleteUser(userId)
{
	if (confirm('Supprimer cet utilisateur?')) {
		window.location.href = 'processUser.php?action=delete&userId=' + userId;
	}
}

