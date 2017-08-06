<?php
/**
 * @version     1.0.0
 * @package     checkmydrive
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Aloud Media Ltd <info@aloud.ie> - http://aloud.ie
 */

$user = Checkmydrive::getUser();
$config  = Checkmydrive::getConfigs();
//k($user->params->google);
?>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angular-ui-router/1.0.0-rc.1/angular-ui-router.min.js"></script>
<script type="text/javascript" src="http://stevenlevithan.com/assets/misc/date.format.js"></script>
<script type="text/javascript">
var driveLanguage = {
    "dashboard": "Dashboard",
    "userAccess": "Users with Access",
    "shared": "Files / Folders Shared Publicly",
    "emptyFiles": "Empty files",
    "name": "Name",
    "accessTo": "Access to",
    "permission": "Permission",
    "warning": "Warning",
    "options": "Options",
    "fileEmpty": "Empty Files",
    "lastOpen": "Last opened",
    "file": {
        "name": "File name",
        "path": "File path",
        "lastOpen": "Last opened",
        "created": "Created",
        "type": "Type"
    }    
}
</script>
<style type="text/css">
    template{display:none;}
</style>
<div id="jk-contents" class="ct_ctn" ng-app="GoogleDrive">

    <div class="contents row-fluid">
        <div id="sidebar-left"><?php echo CheckmydriveHelper::buildSidebar();?></div>
        <div id="content-wrapper">
            
    
    <a ui-sref="dashboard" ui-sref-active="active">Dashboard</a>
    <a ui-sref="user-access" ui-sref-active="active">User access</a>
    <a ui-sref="shared" ui-sref-active="active">Shared</a>
    <a ui-sref="empty" ui-sref-active="active">Empty Files</a>
    
    
    
    
            <ui-view></ui-view>
            <template view-dashboard>                
                <div class="row-fluid ct_subcont">
                    <div class="group">
                        <a ui-sref="user-access"><h2>{{$root.language.userAccess}}</h2></a>
                        <table  class="table table-bordered">
                            <thead>
                                <th>{{$root.language.name}}</th>
                                <th>{{$root.language.file.type}}</th>
                                <th>{{$root.language.accessTo}}</th>
                                <th>{{$root.language.permission}}</th>
                                <th>{{$root.language.warning}}</th>
                                <th>{{$root.language.options}}</th>
                            </thead>
                            <tbody>
                                <tr ng-repeat="permission in model.userAccess">
                                    <td>{{permission.displayName}}</td>
                                    <td><img ng-src="{{permission.file.iconLink}}" title=""/></td>
                                    <td>{{permission.file.name}}</td>
                                    <td>{{permission.role}}</td>
                                    <td>LOW</td>
                                    <td>REMOVE/KEEP</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="group">
                        <a ui-sref="shared"><h2>{{$root.language.shared}}</h2></a>
                        <table class="table table-bordered">
                            <thead>
                                <th>{{$root.language.file.type}}</th>
                                <th>{{$root.language.file.name}}</th>
                                <th>{{$root.language.file.created}}</th>
                                <th>{{$root.language.file.path}}</th>
                                <th>{{$root.language.permission}}</th>
                                <th>{{$root.language.warning}}</th>
                                <th>{{$root.language.options}}</th>
                            </thead>
                            <tbody>
                                <tr ng-repeat="file in model.shared">
                                    <td><img ng-src="{{file.iconLink}}" title=""/></td>
                                    <td>{{file.name}}</td>
                                    <td>{{file.createdTime.toDate('dd/mm/yyyy')}}</td>
                                    <td>Path</td>
                                    <td>{{file.sharedPermission.role}}</td>
                                    <td>LOW</td>
                                    <td>REMOVE/KEEP</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="group">
                        <a ui-sref="empty"><h2>{{$root.language.emptyFiles}}</h2></a>
                        <table class="table table-bordered">
                            <thead>
                                <th>{{$root.language.file.type}}</th>
                                <th>{{$root.language.file.name}}</th>
                                <th>{{$root.language.file.created}}</th>
                                <th>{{$root.language.file.lastOpen}}</th>
                                <th>{{$root.language.file.path}}</th>
                                <th>{{$root.language.options}}</th>
                            </thead>
                            <tbody>
                                <tr ng-repeat="file in model.empty">
                                    <td><img ng-src="{{file.iconLink}}" title=""/></td>
                                    <td>{{file.name}}</td>
                                    <td>{{file.createdTime.toDate('dd/mm/yyyy')}}</td>
                                    <td>{{file.viewedByMeTime.toDate('dd/mm/yyyy')}}</td>
                                    <td>/MyDrive</td>
                                    <td>REMOVE/KEEP</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
            <template view-user-access>                
                <div class="row-fluid ct_subcont">
                        <h2>{{$root.language.userAccess}}</h2>
                    <div class="group">
                        <table class="table table-bordered">
                            <thead>
                                <th>{{$root.language.file.type}}</th>
                                <th>{{$root.language.file.name}}</th>
                                <th>{{$root.language.file.created}}</th>
                                <th>{{$root.language.file.lastOpen}}</th>
                                <th>{{$root.language.file.path}}</th>
                                <th>{{$root.language.options}}</th>
                            </thead>
                            
                            <tbody ng-repeat="(email,user) in model.userAccess">
                                <tr><td colspan="6"><h4>{{user.displayName}}</h4></td></tr>
                                <tr ng-repeat="permission in user.permissions">
                                    <td><img ng-src="{{permission.file.iconLink}}" title=""/></td>
                                    <td>{{permission.file.name}}</td>
                                    <td>{{permission.file.createdTime.toDate('dd/mm/yyyy')}}</td>
                                    <td>{{permission.file.viewedByMeTime.toDate('dd/mm/yyyy')}}</td>
                                    <td>/MyDrive</td>
                                    <td>REMOVE/KEEP</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
            <template view-shared>                
                <div class="row-fluid ct_subcont">
                    <div class="group">
                        <h2>{{$root.language.shared}}</h2>
                        <table class="table table-bordered">
                            <thead>
                                <th>{{$root.language.file.type}}</th>
                                <th>{{$root.language.file.name}}</th>
                                <th>{{$root.language.file.created}}</th>
                                <th>{{$root.language.file.path}}</th>
                                <th>{{$root.language.permission}}</th>
                                <th>{{$root.language.warning}}</th>
                                <th>{{$root.language.options}}</th>
                            </thead>
                            <tbody>
                                <tr ng-repeat="file in model.shared">
                                    <td><img ng-src="{{file.iconLink}}" title=""/></td>
                                    <td>{{file.name}}</td>
                                    <td>{{file.createdTime.toDate('dd/mm/yyyy')}}</td>
                                    <td>Path</td>
                                    <td>{{file.sharedPermission.role}}</td>
                                    <td>LOW</td>
                                    <td>REMOVE/KEEP</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
            
            <template view-empty>                
                <div class="row-fluid ct_subcont">
                    <div class="group">
                        <h2>{{$root.language.emptyFiles}}</h2>
                        <table class="table table-bordered">
                            <thead>
                                <th>{{$root.language.file.type}}</th>
                                <th>{{$root.language.file.name}}</th>
                                <th>{{$root.language.file.created}}</th>
                                <th>{{$root.language.file.lastOpen}}</th>
                                <th>{{$root.language.file.path}}</th>
                                <th>{{$root.language.options}}</th>
                            </thead>
                            <tbody>
                                <tr ng-repeat="file in model.empty">
                                    <td><img ng-src="{{file.iconLink}}" title=""/></td>
                                    <td>{{file.name}}</td>
                                    <td>{{file.createdTime.toDate('dd/mm/yyyy')}}</td>
                                    <td>{{file.viewedByMeTime.toDate('dd/mm/yyyy')}}</td>
                                    <td>/MyDrive</td>
                                    <td>REMOVE/KEEP</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
<script type="text/javascript">
(function($){
    String.prototype.toDate = function(format) {
        return new Date(this).format(format); 
    }
    
    
    var 
        mimeTypes = {
            audio: 'application/vnd.google-apps.audio',
            document: 'application/vnd.google-apps.document',
            drawing: 'application/vnd.google-apps.drawing',
            file: 'application/vnd.google-apps.file',            
            folder: 'application/vnd.google-apps.folder',
            form: 'application/vnd.google-apps.form',
            fusiontable: 'application/vnd.google-apps.fusiontable',
            map : "application/vnd.google-apps.map",
            photo : "application/vnd.google-apps.photo",
            presentation : "application/vnd.google-apps.presentation",
            script : "application/vnd.google-apps.script",
            sites : "application/vnd.google-apps.sites",
            spreadsheet : "application/vnd.google-apps.spreadsheet",
            unknown : "application/vnd.google-apps.unknown",
            video : "application/vnd.google-apps.video",
            driveSdk: "application/vnd.google-apps.drive-sdk",
        }
    ;

    var app = angular.module('GoogleDrive',['ui.router'])
    
    
    
    
    .service('FilesService', ['$rootScope',function(root) {
        var 
            database = {
                permissions: [],
                shared: [],
                emptyFiles: [],
                underfined: []
            },
            files = JSON.parse(localStorage["GoogleDriveFiles"] || '[]'),
            filesModel = [],
            indexs = {
                cached: {}
            },
            changesId = localStorage["GoogleDriveChangesId"],
            m = {
                loadFiles: function(token){
                    gapi.client.drive.files.list ({
                        pageToken: token,
                        fields: 'nextPageToken,incompleteSearch,files',
                        spaces: 'drive',
                        q: '"me" in owners and trashed = false',
                        pageSize: 100
                    }).execute(function(data) {
                        $.each(data.files,function(){                            
                            indexs.cached[this.id] = files.length;
                            files.push(this);
                        });
                        localStorage["GoogleDriveFiles"] = JSON.stringify(files);
                        $scope.triggerHandler('loadedFiles',[data.files]);
                        if(data.nextPageToken){
                            m.loadFiles(data.nextPageToken);
                        }
                    });
                },
                loadChanges: function(){
                    if(changesId){
                        gapi.client.drive.changes.list({
                            pageToken: changesId,
                            spaces: 'drive',
                            fields: 'nextPageToken,newStartPageToken,changes(file,fileId,removed,time,type)',
                            q: '"me" in owners and trashed = false'
                        }).execute(function(data){
                            var changed = false, newFile = [];
                            $.each(data.changes,function(){
                                if(!this.file.ownedByMe) return;
                                var index = indexs.cached[this.file.id];
                                if(index){
                                    files[index] = this.file;                                    
                                    $.extend(filesModel[index],this.file);
                                    changed = true;
                                }else{
                                    indexs.cached[this.file.id] = files.length;
                                    files.push(file);
                                    newFile.push(this.file);
                                }                                
                            });
                            if(changed){
                                $scope.triggerHandler('loadedFiles',[newFile]);
                                setTimeout(function(){ root.$apply();});
                                localStorage["GoogleDriveFiles"] = JSON.stringify(files);
                            }
                            changesId = localStorage["GoogleDriveChangesId"] = data.newStartPageToken;
                        });
                    }else{                        
                        gapi.client.drive.changes
                            .getStartPageToken()
                            .execute(function(data) {
                                changesId = localStorage["GoogleDriveChangesId"] = data.startPageToken
                            });
                    }
                    return arguments.callee;
                }
            },
            $scope = $(root),
            loaded,
            isDetectEmpty = function(file){
                 return file.mimeType !== mimeTypes.folder;
            }
        ;
        
            
        $scope.bind('loadedFiles',function(e,files){
            files = angular.copy(files);
            $.merge(filesModel,files);
            
            $.each(files,function(index,file){
                if(file.permissions){
                    var ps = file.permissions.filter(function(permission){ return permission.role !== "owner" && permission.id !== "anyoneWithLink" });
                    if(ps.length){
                        $.each(ps,function(){
                            this.file = file;
                            database.permissions.push(this);
                        });                        
                    }
                    var shared = file.permissions.find(function(permision){ return permision.id == "anyoneWithLink" });
                    if(shared){
                        database.shared.push(file);
                        file.sharedPermission = shared;
                    }
                }
                if(file.size && parseInt(file.size) == 0){ 
                    database.emptyFiles.push(file);
                }
            });
            //loaded = true;
            setTimeout(function(){ root.$apply();})
        });
        
        if(!files.length){
            $(window).one('gApiLoaded',function(){
                setInterval(m.loadChanges(),10000);
                m.loadFiles();                
            });
        }else{
            $.each(files,function(i){                            
                indexs.cached[this.id] = i;
            });            
            $scope.triggerHandler('loadedFiles',[files]);
            $(window).one('gApiLoaded',function(){
                setInterval(m.loadChanges(),10000);                
            });
        }
        var getData = function(name){}
        var service = {};
        $.each(database,function(k){
            service[k] = function(fn){
                return fn(database[k]);
            }
        });
        return service;
    }])
    
    
    
    
    .run( ['$rootScope', '$state', '$stateParams',
        function (scope, $state, $stateParams) {            
            var $scope = $(scope);
            
            $.extend(scope,{                
                language: driveLanguage
            });
        }
    ])
    
    
    
    
    .component('dashboard',{
        template: $('[view-dashboard]').html(),
        controller: ['$scope','$controller','FilesService',function(scope,controller,service){
            var $root = $(scope.$root);
            $.extend(scope,{
                model: {}
            });
            
            $root.bind('loadedFiles',function(){                
                service.permissions(function(permissions){
                    var has = {};
                    scope.model.userAccess = permissions.filter(function(per){
                        if(has[per.emailAddress]) return false;
                        has[per.emailAddress] = per;
                        return true;
                    }).slice(0,5);
                });
                service.shared(function(shared){
                    scope.model.shared = shared.slice(0,5);
                });

                service.emptyFiles(function(empty){
                    empty.sort(function(a,b){
                        return a.viewedByMeTime > b.viewedByMeTime
                    });
                    scope.model.empty = empty.slice(0,5);
                });
                return arguments.callee;
            }());
        }]
    })
    
    
    
    
    .component('users',{
        template: $('[view-user-access]').html(),
        controller: ['$scope','$controller','FilesService',function(scope,controller,service){
            var $root = $(scope.$root);
            $.extend(scope,{
                model: {userAccess:{}}
            });
            $root.bind('loadedFiles',function(){
                service.permissions(function(permissions){
                    var users = scope.model.userAccess = {};
                    $.each(permissions,function(i,per){
                        users[per.emailAddress] = users[per.emailAddress] || {                        
                            displayName: per.displayName,
                            emailAddress: per.emailAddress,
                            permissions: []
                        };
                        users[per.emailAddress].permissions.push(per);
                    });                
                });
                return arguments.callee;
            }());
        }]
    })
    
    
    
    
    .component('shared',{
        template: $('[view-shared]').html(),
        controller: ['$scope','$controller','FilesService',function(scope,controller,service){
            var $root = $(scope.$root);
            $.extend(scope,{
                model: {}
            });
            $root.bind('loadedFiles',function(){
                service.shared(function(shared){
                    scope.model.shared = shared;
                });
                return arguments.callee;
            }());
        }]
    })
    
    
    
    
    .component('empty',{
        template: $('[view-empty]').html(),
        controller: ['$scope','$controller','FilesService',function(scope,controller,service){
            var $root = $(scope.$root);
            $.extend(scope,{
                model: {}
            });
            $root.bind('loadedFiles',function(){
                service.emptyFiles(function(empty){
                    scope.model.empty = empty;
                });
                return arguments.callee
            }());
        }]
    })
    
                
                
    .config(['$locationProvider', function($locationProvider) {
        $locationProvider.html5Mode(true);
    }]).config(function($stateProvider) {        
        [
            {
                name: 'dashboard',
                url: '',
                component: 'dashboard'
            },
            {
                name: 'user-access',
                url: '/user_access',
                component: 'users'
            },
                    
            {
                name: 'shared',
                url: '/shared',
                component: 'shared'
            },
                    
            {
                name: 'empty',
                url: '/emptyFiles',
                component: 'empty'
            },
                    
            {
                name: 'file.detail',
                url: '/file/{fileId}',
                component: 'file-detail'
            }
        ].forEach(function(state){
            state.url = '/google' + state.url;
            $stateProvider.state(state);
        });
  });
    window.onGApiLoaded = function(){
        gapi.client.setApiKey('AIzaSyBOSX4WnMxNmozqI9oh6UZ-1li5zXMTdTU');
        gapi.client.setToken(<?php echo json_encode($user->params->google)?>);
        gapi.load('client:auth2')
        gapi.client.load('drive', 'v3', function() { $(window).triggerHandler('gApiLoaded'); });
    }
    $.getScript('https://apis.google.com/js/client.js?onload=onGApiLoaded');
})(jQuery);

        
</script>