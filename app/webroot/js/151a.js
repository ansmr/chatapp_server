/*!
 * 151a
 * Copyright (c) 2015 151a
 */
var srvUrl = 'http://nishimuuum.sakura.ne.jp/151a';
var myApp = ons.bootstrap('myApp', [ 'onsen']);
var url = 'https://api-datastore.appiaries.com/v1/dat/_sandbox/group_chat_demo/test1';
var regUserUrl = srvUrl + '/users/add/';
var updUserUrl = srvUrl + '/users/upd/';
var getUsersUrl = srvUrl + '/users/lists/';
var groupJoinUrl = srvUrl + '/user_groups/joining/';
var groupAddUrl = srvUrl + '/groups/add/';
var getGroupUrl = srvUrl + '/user_groups/lists/';
var deleteGroupUrl = srvUrl + '/user_groups/del/';
var messageListUrl = srvUrl + '/messages/lists/';
var messageAddUrl = srvUrl + '/messages/add/';
var user_name = localStorage.getItem("username");
myApp.controller("chat", function($scope, $rootScope) {
    $rootScope.chatId = "8a943d76aeee";
	$rootScope.userId = "d8e6aafe6958e5aa186b2a6fbbeed333";
	//$rootScope.userId = localStorage.getItem("user_id");
	$.ajax({
		'type' : 'POST',
		'url' : messageListUrl + encodeURI($rootScope.chatId),
		'dataType' : 'JSON',
		'headers' : {
			'Content-Type' : 'application/json'
		}
	}).done(function(data, status, xhr) {
		$rootScope.message = "";
		$rootScope.message = data.result;
		console.log($scope.message);
	}).fail(function(data, status, xhr){
		alert("メッセージの取得に失敗しました。");
	});
	$rootScope.addMessage(message) = function(message){
		$data = {
			"sendUser" : $rootScope.userId,
			"message" : message
		}
		$.ajax({
			'type' : 'POST',
			'url' : messageAddUrl + encodeURI($rootScope.chatId),
			'dataType' : 'JSON',
			'headers' : {
				'Content-Type' : 'application/json'
			}
		}).done(function(data, status, xhr) {
			$rootScope.message = "";
			$rootScope.message = data.result;
			console.log($scope.message);
		}).fail(function(data){
			alert("メッセージの取得に失敗しました。");
		});
	}
});

myApp.controller('base', function($scope, $rootScope) {
	$scope.room = {};
	$scope.room.name = "";
	$scope.room.date = "";
	$scope.room.time = "";
	$scope.insert = function() {
		if ($scope.room.name == "") {
			alert("グループ名を入力してね");
			return;
		}
		var group_name = $scope.room.name;
		var limit_date = $scope.room.date;
		var limit_time = $scope.room.time;
		var user_id = localStorage.getItem("user_id");

		var data = {
			"limit_date" : limit_date,
			"limit_time" : limit_time
		};
		$.ajax({
			'type' : 'POST',
			'url' : groupAddUrl + encodeURI(group_name) + "/" + user_id,
			'data' : JSON.stringify(data),
			'dataType' : 'JSON',
			'headers' : {
				'Content-Type' : 'application/json'
			}
		}).done(function(data, status, xhr) {
			alert("チャットルームを作成しました");
			// alert(JSON.stringify(data));
		}).fail(function(data, status, xhr) {
			alert("チャットルームを作成しました");
			// alert("error");
			// alert(JSON.stringify(data));
		}).always(function(data, status, xhr) {
			//共通の処理
			myNavigator.pushPage('top.html');
		});
	};
});

myApp.controller('index', function($scope, $rootScope) {
	$rootScope.get = function() {
		var userId = localStorage.getItem("user_id");
		$.ajax({
			type : 'get',
			url : getGroupUrl + userId,
			headers : {
				'Content-Type' : 'application/json'
			},
			dataType : 'JSON'
		}).done(function(data, status, xhr) {
			$rootScope.someObject = data.result;
		}).fail(function(data, status, xhr) {
			alert("error");
		}).always(function(data, status, xhr) {
			myNavigator.pushPage('main.html');
		});
	};
	//localStorage.clear();
	//ニックネームの有無を確認
	console.log("index start");
	var obj = localStorage.getItem("username");
	if (obj == null || obj == undefined) {
		myNavigator.pushPage("first_start.html");
	} else {
		$rootScope.get();
	}

});

myApp.controller('first_start', function($scope, $rootScope) {
	//init
	$scope.user = {};
	$scope.user.name = "";

	$scope.addUserName = function() {
		var username = $scope.user.name;
		$('#add_username_msg').html("");
		if (username == "") {
			var msg = "ERROR：ユーザ名が空です。";
			$('#add_username_msg').html(msg);
			return;
		}
		$.ajax({
			type : 'post',
			url : regUserUrl + encodeURI(username),
			headers : {
				'Content-Type' : 'application/json'
			},
			dataType : 'JSON',
			scriptCharset : 'utf-8',
		}).done(function(data, status, xhr) {
			localStorage.setItem("username", username);
			localStorage.setItem("user_id", data.result.id);
			alert("ニックネームを登録しました。");
			console.log(data.result.id);
			$rootScope.get();
			// alert(JSON.stringify(data));
		}).fail(function(data, status, xhr) {
			alert("ユーザ登録に失敗しました！");
			localStorage.clear();
			// alert(JSON.stringify(data));
		}).always(function(data, status, xhr) {
			//共通の処理
		});
	};
});

myApp.controller('list', function($scope, $rootScope) {

	$scope.goChat = function(chatId) {
		$rootScope.chatId = chatId;
		myNavigator.pushPage('chat.html');
	};
	$scope.deleteChat = function(chatId) {
		var user_id = localStorage.getItem('user_id');
		$.ajax({
			type : 'post',
			url : deleteGroupUrl + chatId + "/" + user_id,
			headers : {
				'Content-Type' : 'application/json',
				'X-APPIARIES-TOKEN' : 'appb15655d8ce9f55f95e91e6ae7f'
			},
			dataType : 'JSON'
		}).done(function(data, status, xhr) {
			//alert("success");
		}).fail(function(data, status, xhr) {
			alert("削除に失敗しました。");
			console.log(status);
		}).always(function(data, status, xhr) {
			myNavigator.pushPage('top.html');
		});
	};
});

myApp.controller('invite', function($scope, $rootScope) {
	console.log("ユーザ一覧取得start");
	$.ajax({
		type : 'get',
		url : getUsersUrl,
		headers : {
			'Content-Type' : 'application/json',
		},
		dataType : 'JSON'
	}).done(function(data, status, xhr) {
		var arr = [];
		for (var i = 0; data.result.length > i; i++) {
			arr[i] = JSON.parse(JSON.stringify(data.result[i]));
		}
		$scope.prefs = arr;
	}).fail(function(data, status, xhr) {
		alert("error!");
	}).always(function(data, status, xhr) {
	});

	$scope.selectUsers = function() {
		var checks = [];
		angular.forEach($scope.prefs, function(item) {
			if (item.checked)
				checks.push(item.User.uniqid);
		});
		console.log("登録group_id : " + $rootScope.chatId);
		$.each(checks, function(i, val) {
			console.log("登録user_id " + i + ": " + val);
			$.ajax({
				type : 'post',
				url : groupJoinUrl + $rootScope.chatId + '/' + val,
				headers : {
					'Content-Type' : 'application/json'
				},
				dataType : 'JSON',
				scriptCharset : 'utf-8',
			}).done(function(data, status, xhr) {
				if (i == checks.length - 1) {
					alert("ユーザを招待しました。:" + status);
				}
				console.log("ユーザ招待結果：" + status);
			}).fail(function(data, status, xhr) {
				if (i == checks.length - 1) {
					alert("ユーザを招待しました。:");
				}
				console.log("ユーザ招待結果：" + status);
				// alert(status);
			}).always(function(data, status, xhr) {
				//共通の処理
			});
		});
		myModal.hide();
	};
});
myApp.controller('main', function($scope, $rootScope) {
	$scope.username = user_name;
});
myApp.controller('name_edit', function($scope, $rootScope) {
	$scope.user = {};
	$scope.user.name = user_name;
	var user_id = localStorage.getItem("user_id");

	$scope.upd = function() {
		var username = $scope.user.name;
		$.ajax({
			type : 'post',
			url : updUserUrl + user_id + "/" + encodeURI(username),
			headers : {
				'Content-Type' : 'application/json'
			},
			dataType : 'JSON',
			scriptCharset : 'utf-8',
		}).done(function(data, status, xhr) {
			alert("ニックネームを変更しました。");
			localStorage.setItem("username", username);
			user_name = username;
			$rootScope.get();
		}).fail(function(data, status, xhr) {
			alert("ニックネーム変更に失敗しました！");
			localStorage.clear();
		}).always(function(data, status, xhr) {
			//共通の処理
		});
	};
});
