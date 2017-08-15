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
    "options": "Options",
    "fileEmpty": "Empty Files",
    "lastOpen": "Last opened",
    "remove": "Remove",
    "keep": "Keep",
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
    .btnReset{position: absolute; top: 25px; right: 20px; z-index: 1;}
</style>
<div id="jk-contents" class="ct_ctn">

    <div class="contents row-fluid">
        <div id="sidebar-left"><?php echo CheckmydriveHelper::buildSidebar();?></div>
        <div id="content-wrapper" ng-app="GoogleDrive" class="{{loading()?'loading':''}}">
            <button class="btnReset" ng-click="reset()" ng-disabled="loading()">Reset</button>
            <ui-view></ui-view>
            <template view-dashboard>                
                <div class="row-fluid ct_subcont">
                    <div class="group" ng-hide="!model.userAccess.length">
                        <a ui-sref="user-access"><h2>{{$root.language.userAccess}}</h2></a>
                        <table  class="table table-bordered">
                            <thead>
                                <th>{{$root.language.name}}</th>
                                <th>{{$root.language.file.type}}</th>
                                <th>{{$root.language.accessTo}}</th>
                                <th>{{$root.language.permission}}</th>
                                <th>{{$root.language.options}}</th>
                            </thead>
                            <tbody>
                                <tr ng-repeat="permission in model.userAccess" ng-init="file = permission.file()">
                                    <td>{{permission.displayName}}</td>
                                    <td><a href="{{file.webViewLink}}" target="_blank"><img ng-src="{{file.iconLink}}" title=""/></td>
                                    <td><a href="{{file.webContentLink||file.webViewLink}}" target="_blank">{{file.path()}}</a></td>
                                    <td>{{permission.role}}</td>
                                    <td>
                                        <a href="javascript:void(0)" ng-click="removePermission(permission)">{{$root.language.remove}}</a>/<a href="javascript:void(0)" ng-click="keepPermission(permission)">{{$root.language.keep}}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="group" ng-hide="!model.shared.length">
                        <a ui-sref="shared"><h2>{{$root.language.shared}}</h2></a>
                        <table class="table table-bordered">
                            <thead>
                                <th>{{$root.language.file.type}}</th>
                                <th>{{$root.language.file.name}}</th>
                                <th>{{$root.language.file.created}}</th>
                                <th>{{$root.language.file.path}}</th>
                                <th>{{$root.language.permission}}</th>
                                <th>{{$root.language.options}}</th>
                            </thead>
                            <tbody>
                                <tr ng-repeat="file in model.shared">
                                    <td><a href="{{file.webViewLink}}" target="_blank"><img ng-src="{{file.iconLink}}" title=""/></td>
                                    <td><a href="{{file.webContentLink||filewebViewLink}}" target="_blank">{{file.name}}</a></td>
                                    <td>{{file.createdTime.toDate('dd/mm/yyyy')}}</td>
                                    <td><a href="{{file.parent()?file.parent().webViewLink:'https://drive.google.com/drive/my-drive'}}" target="_blank">{{file.dir()}}</a></td>
                                    <td>{{file.sharedPermission().role}}</td>
                                    <td>
                                        <a href="javascript:void(0)" ng-click="removeShared(file)">{{$root.language.remove}}</a>/<a href="javascript:void(0)" ng-click="keepShared(file)">{{$root.language.keep}}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="group" ng-hide="!model.empty.length">
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
                                    <td><a href="{{file.webViewLink}}" target="_blank"><img ng-src="{{file.iconLink}}" title=""/></td>
                                    <td><a href="{{file.webContentLink||filewebViewLink}}" target="_blank">{{file.name}}</a></td>
                                    <td>{{file.createdTime.toDate('dd/mm/yyyy')}}</td>
                                    <td>{{file.viewedByMeTime.toDate('dd/mm/yyyy')}}</td>
                                    <td><a href="{{file.parent()?file.parent().webViewLink:'https://drive.google.com/drive/my-drive'}}" target="_blank">{{file.dir()}}</a></td>
                                    <td>
                                        <a href="javascript:void(0)" ng-click="removeEmpty(file)">{{$root.language.remove}}</a>/<a href="javascript:void(0)" ng-click="keepEmpty(file)">{{$root.language.keep}}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
            <template view-user-access>                
                <div class="row-fluid ct_subcont">
                        <h2>{{$root.language.userAccess}}</h2>
                        <div class="group" ng-hide="isEmpty()">
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
                                <tr ng-repeat="permission in user.permissions" ng-init="file = permission.file()">
                                    <td><a href="{{file.webViewLink}}" target="_blank"><img ng-src="{{file.iconLink}}" title=""/></td>
                                    <td><a href="{{file.webContentLink||file.webViewLink}}" target="_blank">{{file.name}}</a></td>
                                    <td>{{file.createdTime.toDate('dd/mm/yyyy')}}</td>
                                    <td>{{file.viewedByMeTime.toDate('dd/mm/yyyy')}}</td>
                                    <td><a href="{{file.parent()?file.parent().webViewLink:'https://drive.google.com/drive/my-drive'}}" target="_blank">{{file.dir()}}</a></td>
                                    <td>
                                        <a href="javascript:void(0)" ng-click="removePermission(permission)">{{$root.language.remove}}</a>/<a href="javascript:void(0)" ng-click="keepPermission(permission)">{{$root.language.keep}}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
            <template view-shared>                
                <div class="row-fluid ct_subcont">
                    <h2>{{$root.language.shared}}</h2>
                    <div class="group" ng-hide="!model.shared.length">
                        <table class="table table-bordered">
                            <thead>
                                <th>{{$root.language.file.type}}</th>
                                <th>{{$root.language.file.name}}</th>
                                <th>{{$root.language.file.created}}</th>
                                <th>{{$root.language.file.path}}</th>
                                <th>{{$root.language.permission}}</th>
                                <th>{{$root.language.options}}</th>
                            </thead>
                            <tbody>
                                <tr ng-repeat="file in model.shared">
                                    <td><a href="{{file.webViewLink}}" target="_blank"><img ng-src="{{file.iconLink}}" title=""/></td>
                                    <td><a href="{{file.webContentLink||file.webViewLink}}" target="_blank">{{file.name}}</a></td>
                                    <td>{{file.createdTime.toDate('dd/mm/yyyy')}}</td>
                                    <td><a href="{{file.parent()?file.parent().webViewLink:'https://drive.google.com/drive/my-drive'}}" target="_blank">{{file.dir()}}</a></td>
                                    <td>{{file.sharedPermission().role}}</td>
                                    <td>
                                        <a href="javascript:void(0)" ng-click="removeShared(file)">{{$root.language.remove}}</a>/<a href="javascript:void(0)" ng-click="keepShared(file)">{{$root.language.keep}}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
            
            <template view-empty>                
                <div class="row-fluid ct_subcont">
                    <h2>{{$root.language.emptyFiles}}</h2>
                    <div class="group" ng-hide="!model.empty.length">
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
                                    <td><a href="{{file.webViewLink}}" target="_blank"><img ng-src="{{file.iconLink}}" title=""/></td>
                                    <td><a href="{{file.webContentLink||file.webViewLink}}" target="_blank">{{file.name}}</a></td>
                                    <td>{{file.createdTime.toDate('dd/mm/yyyy')}}</td>
                                    <td>{{file.viewedByMeTime.toDate('dd/mm/yyyy')}}</td>
                                    <td><a href="{{file.parent()?file.parent().webViewLink:'https://drive.google.com/drive/my-drive'}}" target="_blank">{{file.dir()}}</a></td>
                                    
                                    <td>
                                        <a href="javascript:void(0)" ng-click="removeEmpty(file)">{{$root.language.remove}}</a>/<a href="javascript:void(0)" ng-click="keepEmpty(file)">{{$root.language.keep}}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
            <div class="loader">
                <div class="spiner sk-rotating-plane">
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
(function($){
    String.prototype.toDate = function(format) {
        return new Date(this).format(format); 
    }
    Array.prototype.remove = function(obj){return this.splice($.inArray(obj,this),1);}
    var DBTable = function(arr){
        arr = arr || [];
        var indexs = {}, indexParam = {};
        $.extend(arr,{
            add: function(doc){
                if(doc.length !== undefined){
                    doc.forEach(function(doc){
                        arr.add(doc);
                    });
                }else{
                    arr.push(doc);
                    $.each(indexParam,function(name,exp){
                        indexs[name][exp(doc)] = doc;
                    });                    
                }
                return this;
            },
            remove: function(name,index){
                if(name instanceof Object ){
                    if(name.length !== undefined ){
                        name.forEach(function(obj){
                            arr.remove(obj);
                        });
                    }else{                        
                        var obj = name;
                        $.each(indexParam,function(name,exp){
                            delete indexs[name][exp(obj)];
                        });
                        arr.splice($.inArray(obj,this),1);
                    }
                }else if(name){
                    this.remove(index[name][index]);
                }
                return this;
            },
            addIndex: function(name,exp){
                if(name instanceof Object){
                    $.each(name,function(name,exp){
                        arr.addIndex(name,exp)
                    });
                }else{
                    indexParam[name] = exp;
                    indexs[name] = {};
                    $.each(arr,function(i,doc){
                        indexs[name][exp(doc)] = doc;
                    });
                }
                return this;
            },
            removeIndex: function(names){
                if(names instanceof String) names = names.split(' ');
                name.forEach(function(name){
                    delete indexParam[name];
                    delete indexs[name];
                });
                return this;
            },
            getBy: function(name,index){
                return indexs[name][index];
            }
        });
        return arr;
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
            database,
            fileCached,
            filesData,
            changesId,
            isLoading = false,
            
            m = {
                initilize: function(){                
                    database = {
                        permissions: new DBTable().addIndex({id: function(per){ return per.id}}),
                        shared: new DBTable().addIndex({id: function(file){ return file.id}}),
                        emptyFiles: new DBTable().addIndex({id: function(file){ return file.id}}),
                        folders: new DBTable().addIndex({id: function(file){ return file.id}}),
                        underfined: new DBTable()
                    }
                    
                    fileCached = JSON.parse(localStorage["GoogleDriveFiles"] || '[]');
                    filesData = new DBTable().addIndex('id', function(file){ return file.id});
                    changesId = localStorage["GoogleDriveChangesId"];
                    
                    if(!fileCached.length){
                        $(window).one('gApiLoaded',function(){
                            m.loadFiles() && m.loadChanges();                
                        });
                    }else{            
                        $scope.triggerHandler('loadedFiles',[fileCached]);
                        $(window).one('gApiLoaded',function(){
                            m.loadChanges();
                        });
                    }
                },
                clearCache: function(){delete localStorage["GoogleDriveFiles"]; delete localStorage["GoogleDriveChangesId"]},
                cacheFiles: function(){localStorage["GoogleDriveFiles"] = JSON.stringify(filesData);},
                loadFiles: function(token){
                    if(isLoading && !token) return;
                    isLoading = true;
                    gapi.client.drive.files.list ({
                        pageToken: token,
                        fields: 'nextPageToken,incompleteSearch,files',
                        spaces: 'drive',
                        q: '"me" in owners and trashed = false',
                        pageSize: 100
                    }).execute(function(data) {
                        $scope.triggerHandler('loadedFiles',[data.files]);
                        if(data.nextPageToken){
                            m.loadFiles(data.nextPageToken);
                        }else{                            
                            isLoading = false;
                        }
                        m.cacheFiles();
                    });
                    return true;
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
                                switch(this.type){
                                    case 'file':
                                        if(this.removed){
                                            rmData.file(filesData.getBy('id',this.fileId));
                                        }else{                                            
                                            if(!this.file.ownedByMe) return;
                                            changed = true;
                                            var exists = filesData.getBy('id', this.file.id);
                                            if(exists){                                  
                                                if(this.file.trashed){
                                                    filesData.remove(exists);
                                                }else{
                                                    $.extend(exists,this.file);
                                                }                                    
                                            }else{
                                                newFile.push(this.file);
                                            }
                                            
                                        }
                                        break;
                                }                                
                            });
                            if(changed){           
                                $scope.triggerHandler('loadedFiles',[newFile]);
                                m.cacheFiles();
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
            //files = angular.copy(files);
            //filesModel.add(files);
//            console.log(files);
            var pers = [],shareds = [],emptys = [],folders = [];
            $.each(files,function(index,file){
                if(file.trashed) return;
                if(file.permissions){
                    $.each(file.permissions,function(index,permission){
                        this.file = function(){ return file};
                        file.sharedPermission = function(){return permission};
                        if(permission.id == "anyoneWithLink"){
                            shareds.push(file);
                            file.sharedPermission = function(){ return };
                        }else if(permission.role !== "owner"){
                            pers.push(this);
                        }
                        
                    });
                }
                
                if(file.size && parseInt(file.size) == 0){ 
                    emptys.push(file);
                }
                if(file.mimeType == mimeTypes.folder){
                    folders.push(file);
                }
                file.parent = function(){
                    return database.folders.getBy('id',file.parents[0]);
                }
                file.path = function(){
                    var parent = file.parent(), path = parent?parent.path(): 'Drive';
                    
                    return path + '/' + file.name;
                }
                file.dir = function(){
                    var parent = file.parent(), path = parent?parent.path(): 'Drive';
                    return path + '/';
                }
                file.files = function(){
                    return fileCached.getBy('id', file.id);
                }
            });
            
            database.permissions.add(pers);
            database.shared.add(shareds);
            database.emptyFiles.add(emptys);
            database.folders.add(folders);
            filesData.add(files);
            setTimeout(function(){ root.$apply();})
        });
        var rmData = {
            permission: function(permission,callback){
                permission.file().permissions.remove(permission);
                database.permissions.remove(permission);
                filesData.getBy('id',permission.file().id).permissions.remove();                
                m.cacheFiles();
                callback && callback(database.permissions);
            },
            shared: function(shared,callback){
                shared.permissions.remove(shared.sharedPermission());
                database.shared.remove(shared);
                m.cacheFiles();
                callback && callback(database.shared);
            },
            file: function(file,callback){
                database.emptyFiles.remove(file);
                filesData.remove(file);                
                m.cacheFiles();
                callback && callback(database.emptyFiles);
            }
        }
        
        m.initilize();
        var service = {
            reset: function(){
                if(isLoading) return;
                m.clearCache();
                m.initilize();
                $(window).triggerHandler('gApiLoaded');
            },
            removePermission: function(permission,callback){
                gapi.client.drive.permissions
                    .delete({fileId: permission.file().id, permissionId: permission.id})
                    .execute(function(){
                        rmData.permission(permission,callback);
                        setTimeout(function(){ root.$apply();});
                    });
                
            },
            keepPermission: function(permission,callback){
                rmData.permission(permission,callback);
            },
            removeShared: function(file,callback){
                gapi.client.drive.permissions
                    .delete({fileId: file.id, permissionId: file.sharedPermission().id})
                    .execute(function(){
                        rmData.shared(file,callback);
                        setTimeout(function(){ root.$apply();});
                    });
            },
            keepShared: function(file,callback){
                rmData.shared(file,callback);
            },
            removeFile: function(file,callback){
                gapi.client.drive.files.delete({
                    fileId: file.id
                }).execute();
                rmData.file(file,callback);
            },
            keepFile:  function(file,callback){
                rmData.file(file,callback);
            },
            loading: function(){
                return isLoading;
            }
        };
        $.each(database,function(k){
            service[k] = function(fn){
                return fn(database[k]);
            }
        });
        return service;
    }])
    
    
    
    
    .run( ['$rootScope', '$state', '$stateParams','FilesService',
        function (scope, $state, $stateParams,server) {            
            var $scope = $(scope);
//            scope.$on('$locationChangeStart', function(event, toUrl) {
//                //event.preventDefault()
//                //location.href = toUrl;
//               
//                console.log($state)
//            });
            $.extend(scope,{                
                language: driveLanguage,
                reset: function(){server.reset()},
                loading: function(){ return server.loading();}
            });
            $('#menu-google').parent().on('click','a[ui-sref]',function(e){
                var This = $(this),sref = This.attr('ui-sref');
                if($state.target(sref).valid()){
                    $state.go(sref);
                    return false;
                }
            });
        }
    ])
    
    
    
    
    .component('dashboard',{
        template: $('[view-dashboard]').html(),
        controller: ['$scope','$controller','FilesService',function(scope,controller,service){
            var $root = $(scope.$root);
            var prepair = {
                permission: function(permissions){
                    var has = {};
                    scope.model.userAccess = permissions.filter(function(per){
                        if(has[per.emailAddress]) return false;
                        has[per.emailAddress] = per;
                        return true;
                    }).slice(0,5);
                },
                shared: function(shared){
                    scope.model.shared = shared.slice(0,5);
                },
                empty: function(empty){
                    empty.sort(function(a,b){
                        return a.viewedByMeTime > b.viewedByMeTime
                    });
                    scope.model.empty = empty.slice(0,5);
                }
            }
            
            $.extend(scope,{
                model: {},
                removePermission: function(permission){
                    service.removePermission(permission,prepair.permission);
                },
                keepPermission: function(permission){
                    service.keepPermission(permission,prepair.permission);                    
                },
                removeShared: function(file){
                    service.removeShared(file,prepair.shared);
                },
                keepShared: function(file){
                    service.keepShared(file,prepair.shared);
                },
                removeEmpty:function(file){
                    service.removeFile(file,prepair.empty);
                },
                keepEmpty:function(file){
                    service.keepFile(file,prepair.empty);
                }
            });
            
            $root.bind('loadedFiles',function(){
                service.permissions(prepair.permission);
                service.shared(prepair.shared);

                service.emptyFiles(prepair.empty);
                return arguments.callee;
            }());
        }]
    })
    
    
    
    
    .component('users',{
        template: $('[view-user-access]').html(),
        controller: ['$scope','$controller','FilesService',function(scope,controller,service){
            var $root = $(scope.$root);
            $.extend(scope,{
                model: {userAccess:{}},
                isEmpty: function(){
                    return $.isEmptyObject(scope.model.userAccess);
                },
                removePermission: function(permission){
                    service.removePermission(permission,prepairPermission);
                },
                keepPermission: function(permission){
                    service.keepPermission(permission,prepairPermission);                    
                }
            });
            var prepairPermission = function(permissions){
                var users = scope.model.userAccess = {};
                $.each(permissions,function(i,per){
                    users[per.emailAddress] = users[per.emailAddress] || {                        
                        displayName: per.displayName,
                        emailAddress: per.emailAddress,
                        permissions: []
                    };
                    users[per.emailAddress].permissions.push(per);
                });                
            }
            $root.bind('loadedFiles',function(){
                service.permissions(prepairPermission);
                return arguments.callee;
            }());
        }]
    })
    
    
    
    
    .component('shared',{
        template: $('[view-shared]').html(),
        controller: ['$scope','$controller','FilesService',function(scope,controller,service){
            var $root = $(scope.$root);
            $.extend(scope,{
                model: {},
                removeShared: function(file){
                    service.removeShared(file,function(shared){
                        scope.model.shared = shared;
                    });
                },
                keepShared: function(file){
                    service.keepShared(file,function(shared){
                        scope.model.shared = shared;
                    });
                }
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
                model: {},
                removeEmpty:function(file){
                    service.removeFile(file,function(empty){
                        scope.model.empty = empty;
                    });
                },
                keepEmpty:function(file){
                    service.keepFile(file,function(empty){
                        scope.model.empty = empty;
                    });
                }
            });
            $root.bind('loadedFiles',function(){
                service.emptyFiles(function(empty){
                    scope.model.empty = empty;
                });
                return arguments.callee
            }());
        }]
    })
    
                
                
    .config(function($locationProvider,$urlRouterProvider) {
        $locationProvider.html5Mode(true);
        $urlRouterProvider.when('/google/','/google').when('/index.php/google','/google');
    }).config(function($stateProvider) {   
//$stateProvider.when('/zone','/zone/');     
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