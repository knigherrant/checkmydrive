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
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
<style type='text/css'>
    .progress{ border: 1px solid black; }
    .progress> div{
        height: 20px;
        background: brown;
        width: 0;
    }
    
    .listfiles{ height: 500px; overflow: auto;}
    .listfiles .num{ float:left; width: 40px;} 
    .listfiles .fname{ float: left; }
    
    .listfiles .type{float: right;}
    .listfiles .status{float: right; width: 60px;}
    .listfiles > div:after{content: ''; clear: both; display: block;}
    
</style>
<script type="text/javascript">
    (function(){
       var options = {
            query: 2,
            url: 'http://localhost/checkmydrive/google/'
       };
        var bathFiles = angular.module('batchFiles', []);
        bathFiles.controller('bathSchedule', function($scope,$http) {
            angular.extend($scope, {                
                countQuery: 0,
                countIndex: 0,
                countFinish: 0,
                files: [],
            });
            
            var getList = function(token){
                $http.get(options.url + 'get_files',{
                    params: {pagetoken:token}
                }).then(function(response) {
                    $.each(response.data.files,function(){
                       if(this.permissions) console.log(this) 
                    });
                    $scope.files = $scope.files.concat(response.data.files);
                    if(response.data.nextPageToken){
                        getList(response.data.nextPageToken);
                    }
                    //updateFiles();
                });                
            }
            
            
            var updateFiles = function(){
                while($scope.countQuery < options.query && $scope.countIndex < $scope.files.length){
                    $http.get(options.url + 'update_file',{
                        params: {file: $scope.files[$scope.countIndex].id}
                    }).then(function(response) {
                        $scope.countQuery--;
                        console.log(response);
                        updateFiles();
                    });
                    
                    $scope.countQuery++;
                    $scope.countIndex++;
                    $scope.percent = {width: $scope.countIndex/$scope.files.length * 100 + '%'};
                    console.log($scope.countIndex);
                }
            }
            getList();
        }); 
    })();    
</script>


<div id="jk-contents" class="ct_ctn" ng-app="batchFiles">
    <div class="contents row-fluid"  ng-controller="bathSchedule">
        <div class="row-fluid ct_subcont">
            <h1>Please don't close this progress</h1>
            <div class="progress">
                <div ng-style="percent"></div>
            </div>
            <div class="listfiles">
                <div ng-repeat="file in files" >
                    <div class="num">{{$index+1}}</div>
                    <div class="fname">{{file.name}}</div>
                    <div class="type">{{file.mimeType}}</div>
                </div>
            </div>
        </div>
    </div>
</div>