var selectedGroupId = 1;
var clickedUserId = -1;
var dataTable;
var SEpath = ""; //only use when zend/public is not the root folder on the http server
var theTree;

function makeLinks() {
	$("#EditGroupLink").click(function() {
		loadIntoModalholder(SEpath + '/groups/editgroup', {
			gid : selectedGroupId
		});
	});

	$("#CreateSubGroupLink").click(function() {
		loadIntoModalholder(SEpath + '/groups/addsubgroup', {
			gid : selectedGroupId
		});
	});

	$("#RemoveGroupLink").click(function() {
		loadIntoModalholder(SEpath + '/groups/deletegroup', {
			gid : selectedGroupId
		});
	});

	$("#CreateUserLink").click(function() {
		loadIntoModalholder(SEpath + '/users/userform?state=new', {
			gid : selectedGroupId
		});
	});

	$("#ViewUserLink").click(function() {
		loadIntoModalholder(SEpath + '/users/userform?state=show', {
			pid : clickedUserId
		});
	});

	$("#EditUserLink").click(function() {
		loadIntoModalholder(SEpath + '/users/userform?state=edit', {
			pid : clickedUserId
		});
	});

	$("#ResetPwGroupLink").click(function() {
		loadIntoModalholder(SEpath + '/users/resetpw', {
			selectedGroup : selectedGroupId
		});
	});

	$("#ResetPwSelectedLink").click(function() {
		var selectedArr = getSelectedUsers();

		loadIntoModalholder(SEpath + '/users/resetpw', {
			selectedUsers : selectedArr
		});
	});

	$("#ResetPwUserLink").click(function() {
		loadIntoModalholder(SEpath + '/users/resetpw', {
			pid : clickedUserId
		});
	});

	$("#MoveUserLink").click(function() {
		var selectedArr = getSelectedUsers();

		loadIntoModalholder(SEpath + '/users/move', {
			selectedUsers : selectedArr
		});
	});

	$("#RemoveUserLink").click(function() {
		var selectedArr = getSelectedUsers();

		$("#modalholder").load(SEpath + '/users/remove', {
			selectedUsers : selectedArr
		});
	});

	$("#TaskForUser").click(function() {
		var selectedArr = getSelectedUsers();

		loadIntoModalholder(SEpath + '/usertasks/showtask', {
			selectedUsers : selectedArr
		});
	});

	$("#TemplateTaskForUser").click(function() {
		var selectedArr = getSelectedUsers();

		loadIntoModalholder(SEpath + '/Usertasks/managetasktemplates', {
			selectedUsers : selectedArr
		});
	});

	$("#TaskForGroup").click(function() {
		loadIntoModalholder(SEpath + '/usertasks/showtask', {
			selectedGroup : selectedGroupId
		});
	});

	$("#TemplateTaskForGroup").click(function() {
		loadIntoModalholder(SEpath + '/usertasks/managetasktemplates', {
			selectedGroup : selectedGroupId
		});
	});

	$("#RemoveUsersInGroupLink").click(function() {
		$("#modalholder").load(SEpath + '/users/remove', {
			selectedGroup : selectedGroupId
		});
	});

	$("#MoveUsersInGroupLink").click(function() {
		loadIntoModalholder(SEpath + '/users/move', {
			selectedGroup : selectedGroupId
		});
	});
	
	$("#searchUsersLink").click(function() {
		loadIntoModalholder(SEpath + '/users/search', {
			selectedGroup : selectedGroupId
		});
	});
	
	$("#selectAllCheckbox").click(function() {

		if ($(this).attr("checked")) {
			$(".selectUser").attr("checked", "checked");
		} else {
			$(".selectUser").removeAttr("checked");
		}

	});
	
	$("#mailGroup").click(function() {
		$.get(SEpath + '/groups/sendmail', {
			selectedGroup : selectedGroupId
		}, function(data){
			window.location.href = "mailto:" + data;
		});
	});
		
}

function setGroupSearchField(valueToSet)
{
	$("#userstable_filter input").val(valueToSet).trigger('keyup');
}

function getSelectedUsers() {
	var selectedArr = new Array();

	$(".selectUser:checked").each(function(k, v) {
		selectedArr.push($(v).attr("value"));
	});

	return selectedArr;
}

function loadIntoModalholder(path, extradata) {
	$("#modalholder").html($("#loadingHolder").html());
	$("#modalholder").load(path, extradata);
	isSubmitting = false; // reset double submit var
}

var isSubmitting = false;

function preventDoubleSubmit(formData, jqForm, options) {

	if (isSubmitting) {
		return false;
	} else {
		isSubmitting = true;
		return true;
	}
}



function selectUserinGroup(selectedGroupId,username)
{
	$("#grouptree").jstree("deselect_all");
	$("#grouptree").jstree("select_node","#tree" + selectedGroupId);
		
	setGroupSearchField(username);
}

function updateTree() {
	$("#grouptree").jstree({
		"ui" : {
			"initially_select" : [ "tree" + selectedGroupId ]
		},
		"json_data" : {
			"ajax" : {
				"url" : SEpath + '/groups/getgroup',
				"data" : function(n) {
					return {
						id : n.attr ? n.attr("id") : 0
					};
				},			
				"progressive_render" : true,
				"progressive_unload" : true
			}
		},
		"plugins" : [ "themes", "json_data", "ui" ]
	}).bind("select_node.jstree", function(event, data) {
		
		selectedGroupId = data.rslt.obj.attr("groupid");

		groupname = data.rslt.obj.attr("groupname");

		$("#groupname").html(groupname);
       
		updateUsers();

	});

}

function updateUsers() {
	if ($("#userstable").length) {
		$("#userstable").dataTable().fnReloadAjax(
				SEpath + '/users/getusers?gid=' + selectedGroupId);
	}

	$("#bofhexcuse").html(NewExcuse());
}

function showUsers() {
	dataTable = $("#userstable")
			.dataTable(
					{
						"bSort" : true,
						"aaSorting" : [ [ 6, "desc" ] ],
						"aoColumns" : [ {
							"bSortable" : false
						}, null, null, null, null, null,null ],
						"iDisplayLength" : -1,
						// "aLengthMenu": [[-1, 10, 25, 50], ["All",10, 25,
						// 50]],
						// "sDom": '<"row-fluid"fl><"row-fluid"<"btn-group
						// span6"p><"span6"i>>r<"usertablewrapper"t>',
						"sDom" : '<"row-fluid"f>r<"usertablewrapper"t>',
						"bProcessing" : true,
						"oLanguage" : {
							"sProcessing" : "Data ophalen...",
							"sLengthMenu" : "Aantal resultaten per pagina: _MENU_ ",
							"sZeroRecords" : "Geen resultaten gevonden",
							"sInfo" : "_START_ tot _END_ van _TOTAL_ resultaten",
							"sInfoEmpty" : "Geen resultaten om weer te geven",
							"sInfoFiltered" : " (gefilterd uit _MAX_ resultaten)",
							"sInfoPostFix" : "",
							"sSearch" : "Zoeken in deze groep: (<a href='javascript:void(0)' id='searchUsersLink'>Zoek in alle</a>)",
							"sEmptyTable" : "Geen resultaten aanwezig in de tabel",
							"sInfoThousands" : ".",
							"sLoadingRecords" : "Een moment geduld aub - bezig met laden...",
							"oPaginate" : {
								"sFirst" : "Eerste",
								"sLast" : "Laatste",
								"sNext" : "Volgende",
								"sPrevious" : "Vorige"
							}
						},
						"fnRowCallback" : function(nRow, aData, iDisplayIndex) {
							/* Append the grade to the default row class name */

							$('td:eq(0)', nRow).html(
									"<input class='selectUser' type=checkbox name='user[]' value='"
											+ aData[0] + "'/>");
							

							var accountstatus = "";
							
							$.each(aData[5],function(k,v){
								accountstatus += k;
								accountstatus += v;
								accountstatus += " ";
							}); 
							
							$('td:eq(5)', nRow).html(accountstatus);
						}

					});

	// table rightclick
	$('#userstable tbody').on("contextmenu", 'tr', function(e) {
		var clickedElem = $(this);

		$("#useractions > ul > li").addClass('open');

		clickedUserId = clickedElem.find(".selectUser").attr("value");

		$("#useractions").css({
			position : "absolute",
			top : e.pageY + "px",
			left : e.pageX + "px"
		});

		return false;
	});

	$('html').on('click', function() {
		$("#useractions > ul > li").removeClass('open');
	});

}