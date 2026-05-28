<?php
/**
 * @copyright 2019   Юнивер ТВ
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../config.php');

$userid = 0;    // User id.

/*if (!$user = $DB->get_record('user', array('id' => $userid))) {
    print_error('invaliduserid');
}




if(is_siteadmin() )
    $is_admin = "true";
else    
    $is_admin = "";

*/


try {  
    $dbh = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $passwd);  
     //$dbh = new PDO("sqlite:/var/www/html/videolan/videolan.db3");  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}


$is_siteadmin = 0;
$customer = 0;
session_start();
if (!isset($_SESSION['user_id']) or $_SESSION['user_id']==0 or $_SESSION['user_id']=='') {
    $user_id = 0;
    $customer_id  = 0;

    $rc_list = [ "status"=>"1", "error"=>'No auth' ];
    $result = ["isSysAdmin"=>0, "userId"=>$user_id, "customerId"=>0, "userId"=>0, "action"=>"action", "result"=>$rc_list];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    return 0;
}

$user_id = $_SESSION['user_id'];
$stmt = $dbh->prepare('SELECT `customer_id`,`role` FROM `users` WHERE `user_id`=? ');
$stmt->execute([$user_id]); 
if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
    $customer = $row->customer_id;
    if($row->role == 'admin')
	$is_siteadmin = 1;
}

#echo $user_id; 



/* VIDEO */

//if($_GET['request']=='video_save'  && $_GET['video_save']!='' && $_GET['video_save']>=0) {
if($_POST['request']=='video_save'  && $_POST['video_id']!='' && $_POST['video_id']>=0) {
    $customer_id = $_POST['customer'];
    if(! $is_siteadmin )
        $customer_id = $customer;

    if( $_POST['video_id']!="" &&   $_POST['video_id']>0 &&  $_POST['name']!='' ){
        $video_id = $_POST['video_id'];
	$stmt = $dbh->prepare('UPDATE `videos` SET  `name`=?, `category_id`=?  WHERE `video_id`=?' );
	$stmt->execute([$_POST['name'],  $_POST['category_id'], $video_id]); 
	if(!$stmt) {
            $rc_list = [ "status"=>"4", "error"=>'error 4: '. $dbh->errorInfo()[2] ];
	}
	else {
            $rc_list = [ "status"=>"0", "error"=>''  ];
	}
    }
    else if( $_POST['video_id']=="" ||  $_POST['video_id']==0 ){
	$customer_name = "_$customer_id_";
	$stmt = $dbh->prepare('SELECT  `name` FROM `customers` WHERE `customer_id`=?');
	$stmt->execute([$customer_id]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $customer_name = $row->name;
	}
	$current_dir =  $videolan_uploads_dir . 'videos/'. $customer_name .'/';
	#$current_dir =  $videolan_uploads_dir . 'videos/'. $customer_id .'/';
	$upload_dir =  $videolan_install_dir . $current_dir;
	$f_name = rus2translit(basename($_FILES['mpfile']['name']));
        while(file_exists($upload_dir . $f_name)) {
	    $a_name = explode(".", $f_name);
	    $i = count($a_name)-2;
	    if($i<0)
		$i = 0;
	    $a_name[$i] = $a_name[$i] . '_';
	    $f_name = implode (".", $a_name);
	}
	$filename = $current_dir . $f_name;
	$uploadfile = $upload_dir . $f_name;
        while(file_exists($uploadfile)) {
	    $uploadfile = $uploadfile.'_';
	    $filename = $filename.'_';
	}
	$file_type = $_FILES['mpfile']['type'];
	mkdir($upload_dir, 0777, true);
        if (move_uploaded_file($_FILES['mpfile']['tmp_name'],  $uploadfile)) {
	    $videoname = $_POST['name'];
	    if($videoname=='') {
		$videoname = explode(".", basename($_FILES['mpfile']['name']))[0];
	    }
	    $stmt = $dbh->prepare('INSERT INTO `videos`(`name`, `file`, `customer_id`, `category_id`) VALUES(?, ?, ?, ?)');
	    $stmt->execute([$videoname, $filename, $customer_id, $_POST['category_id'] ]);
	    if(!$stmt) {
        	$rc_list = [ "status"=>"5", "error"=>'error 5: ' .$dbh->errorInfo()[2] ];
	    }
	    else {
		$video_id = $dbh->lastInsertId(); 
        	$rc_list = [ "status"=>"0", "error"=>'' ];
	    }
	}
	else {
	    $msg2= json_encode($_FILES, JSON_UNESCAPED_UNICODE);
    	    $rc_list = [ "status"=>"6", "error"=>'error 6: ошибка загрузки файла: '. $msg2 ];
	}
    }

    $result = ["isSysAdmin"=>$is_siteadmin, "customerId"=>$customer_id, "userId"=>$user_id, "action"=>"video_save", "result"=>$rc_list];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

if($_POST['request']=='video_upload' ) {
    $customer_id = $_POST['customer'];
    if(! $is_siteadmin )
            $customer_id = $customer;

    $playlist_id = explode(',', $_POST['playlist_id']);
    $playlist_list = explode(',',$_POST['playlist_list']);

    $customer_name = "_$customer_id_";
    $stmt = $dbh->prepare('SELECT  `name` FROM `customers` WHERE `customer_id`=?');
    $stmt->execute([$customer_id]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $customer_name = $row->name;
    }
    $current_dir =  $videolan_uploads_dir . 'videos/'. $customer_name .'/';
    #$current_dir =  $videolan_uploads_dir . 'videos/'. $customer_id .'/';
    $upload_dir =  $videolan_install_dir . $current_dir;

    $countfiles = count($_FILES['files']['name']);
    for($index = 0; $index < $countfiles; $index++){
      //if(isset($_FILES['files']['name'][$index]) && $_FILES['files']['name'][$index] != ''){
    //      $filename = $_FILES['files']['name'][$index];

        $video_id = 0;
	$f_name = rus2translit(basename($_FILES['files']['name'][$index]));
        while(file_exists($upload_dir . $f_name)) {
	    $a_name = explode(".", $f_name);
	    $i = count($a_name)-2;
	    if($i<0)
		$i = 0;
	    $a_name[$i] = $a_name[$i] . '_';
	    $f_name = implode (".", $a_name);
	}
	$filename = $current_dir . $f_name;
	$uploadfile = $upload_dir . $f_name;
        while(file_exists($uploadfile)) {
	    $uploadfile = $uploadfile.'_';
	    $filename = $filename.'_';
	}
	$file_type = $_FILES['files']['type'][$index];
	mkdir($upload_dir, 0777, true);
        if (move_uploaded_file($_FILES['files']['tmp_name'][$index],  $uploadfile)) {
	    $videoname = $_POST['name'][$index];
	    if($videoname=='' || $videoname=="undefined") {
		$videoname = explode(".", basename($_FILES['files']['name'][$index]))[0];
	    }
	    $stmt = $dbh->prepare('INSERT INTO `videos`(`name`, `file`, `customer_id`, `category_id`, `group_id`) VALUES(?, ?, ?, ?, ?)');
	    $stmt->execute([$videoname, $filename, $customer_id, $_POST['category_id'], $_POST['group_id'] ]);
	    if(!$stmt) {
        	$rc_list = [ "status"=>"5", "error"=>'error 5: ' .$dbh->errorInfo()[2] ];
	    }
	    else {
		$video_id = $dbh->lastInsertId(); 
        	$rc_list = [ "status"=>"0", "error"=>'' ];
	    }
	    for($i = 0; $i < count($playlist_id); ++$i) {
    		if( $playlist_id[$i] == 'true'  && $video_id>0){
                    $number = 0;
    		    $stmt2 = $dbh->prepare('SELECT `number` FROM  `playlist_items` WHERE `playlist_id`=? order BY `number` DESC LIMIT 1)');
    		    $stmt2->execute([$playlist_list[$i]]);
		    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
			    $number = $row2->number + 1;
		    }
    		    $stmt2 = $dbh->prepare('INSERT INTO `playlist_items`(`playlist_id`, `video_id`, `number`) VALUES(?, ?, ?)');
    		    $stmt2->execute([$playlist_list[$i], $video_id, $number ]);

//file_put_contents('/var/www/html/list.txt',  $number .", ",  FILE_APPEND);
    		}
	    }




	}
	else {
	    $msg2= json_encode($_FILES, JSON_UNESCAPED_UNICODE);
	    $msg3= json_encode($_POST['name'], JSON_UNESCAPED_UNICODE);
    	    $rc_list = [ "status"=>"6", "error"=>'error 6: ошибка загрузки файла: '. $msg2. '; total: '. $countfiles . '; '. $msg3 ];
	}
    }

    $result = ["isSysAdmin"=>$is_siteadmin, "customerId"=>$customer_id, "userId"=>$user_id, "action"=>"video_save", "result"=>$rc_list];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



else {
    $msg1= json_encode($_POST, JSON_UNESCAPED_UNICODE);
    $msg2= json_encode($_FILES, JSON_UNESCAPED_UNICODE);
    $rc_list = [ "status"=>"7", "error"=>'error 7: ошибка загрузки файла: '. $msg1 . ' : '. $msg2. '; total: '. $countfiles ];
    $result = ["isSysAdmin"=>$is_siteadmin, "customerId"=>$customer_id, "userId"=>$user_id, "action"=>"video_save", "result"=>$rc_list];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function rus2translit($str) {
	mb_regex_encoding('UTF-8');

        $str2 = str_replace(
            array(" ", "(", ")", ",", ":", ";", "+", "/", "\\", "'",  "\"", "-", "`"),
            array("_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_"),
            //array(" ", "(", ")", ".", ",", ":", ";", "+", "/", "\\", "'",  "\"", "-", "`"),
            //array("_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_"),
            $str
        );
        
        $str3 = str_replace(
            array("а", "б", "в", "г", "д", "е", "з", "и", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "ц", "ъ", "ы", "ь"),
            array("a", "b", "v", "g", "d", "e", "z", "i", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "с", "", "y", ""),
            $str2
        );
               
        $str4 = str_replace(
            array("А", "Б", "В", "Г", "Д", "Е", "З", "И", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Ц", "Ъ", "Ы", "Ь"),
            array("A", "B", "V", "G", "D", "E", "Z", "I", "K", "L", "M", "N", "O", "P", "R", "S", "T", "U", "F", "С", "", "Y", ""),
            $str3
        );
        
        $str5 = str_replace(
            array("э", "х", "й", "ё", "ж", "ч", "ш", "щ", "ю", "я", "Э", "Х", "Й", "Ё", "Ж", "Ч", "Ш", "Щ", "Ю", "Я"),
            array("eh", "kh", "jj", "jo", "zh", "ch", "sh", "shh", "ju", "ja", "EH", "KH", "JJ", "JO", "ZH", "CH", "SH", "SHH", "JU", "JA"),
            $str4
        );
   
        return $str5;
}

?>






Vue.component('video-upload', {
  data: function () {
    return {
      info: [],
      info2: [],
      info3: [],
      info4: [],
      currentCustomer: this.$route.params.currentCustomer,
      userId: -1,
      customerId: this.$route.params.customerId,
      categoryId: this.$route.params.categoryId,
      scroll: this.$route.params.scroll,
      message: '',
      category_array: [],
      videofile: '',
      wait: '',
      totalfiles: 0,
      filenames: [],
      names: [],
      videofile2: [],
      groupId: 0,
      group_array: [],
      playlistId: [],
      playlist_array: [],
      playlist_list: []
    }
  },
  mounted() {
    axios
      .post(VideolanApiURL+'video_json.php?request=video_detalies&video_id='+ this.videoId+'&customer='+this.customerId, {video_detalies: {customer: this.customerId, video_id: this.videoId}} )
      .then(response => {
            this.info = response.data
            this.userId = this.info.userId
            this.name = this.info.info.name
            this.filename = this.info.info.file
            if( this.info.result.categoryId >0 ){
                    this.categoryId = this.info.result.categoryId
            }
        })
      .catch(error => {
              console.log(error.response)
            }),

    axios
      .post(VideolanApiURL+'video_json.php?request=categories_list&categories='+this.customerId, {categories_list: {currentCustomer: this.customerId}})
      .then(response => {
            this.info2 = response.data
            this.category_array = this.info2.result[0].list
            if( this.categoryId == 0 ){
                   this.categoryId = this.category_array[0]["categoryId"];
            }
        })
      .catch(error => {
              console.log(error.response)
            }),

    axios
      .post(VideolanApiURL+'video_json.php?request=groups_list', {groups_list: {currentCustomer: this.customerId}})
      .then(response => {
            this.info3 = response.data
            this.group_array = this.info3.result
        })
      .catch(error => {
              console.log(error.response)
            }),

    axios
      .post(VideolanApiURL+'playlist_json.php?request=groups_list', {playlists_list: {customerId: this.customerId, short: 1}})
      .then(response => {
            this.info4 = response.data
            this.playlist_array = this.info4.result[0].list
            for (var i = 0; i < this.playlist_array.length; i++) {
                  if(this.playlist_array[i].name!='')
                          this.playlist_list[i] = this.playlist_array[i].playlistId;
            }
        })
      .catch(error => {
              console.log(error.response)
            })


   },
    methods: {

        handleFileUpload(){
            this.totalfiles = document.getElementById('videofile').files.length;
            this.filenames = document.getElementById('videofile').files;
        },


        uploadVideo(){
            this.wait = 1;
            var formData = new FormData();
            formData.append('request', 'video_upload');
            formData.append('customer', this.customerId);
            formData.append('name', this.name);
            formData.append('category_id', this.categoryId);
            formData.append('group_id', this.groupId);
            formData.append('playlist_id', this.playlistId);
            formData.append('playlist_list', this.playlist_list);

            for (var index = 0; index < this.totalfiles; index++) {
                formData.append("files[]", this.filenames[index]);
                formData.append("name[]", this.names[index]);
            }

            axios
            .post(VideolanApiURL+'video_save_json.php', formData, {headers: {"Content-Type": "multipart/form-data"}})
            .then(response => {
              console.log(response)
              if(response.data.result.status>0) {
                    this.message = response.data.result.error
                    this.wait = 0;
              }
              else {
                   this.$router.push({ name: 'videos', params: { customerId: this.currentCustomer, categoryId: this.categoryId, scroll: this.scroll }}) 
              }
            })
            .catch(error => {
              console.log(error.response)
            })
        }

    },
   
      
  template: `<div>
     <div class="content">
      <nomenu :isSysAdmin=this.isSysAdmin  :userId=this.userId></nomenu>
       <div class="columns">
         <div class="column"> </div>
         <div class="column is-four-fifths">
           <div class="box">

<h4 style="text-align: center; color: red;">{{message}}</h4>

<p><small>Суммарный  размер  файлов не более 1Gb. В имени файла допустиы только латинские буквы и цифры.</small></p>
<div class="file has-name">
  <label class="file-label">
    <input class="file-input" type="file" name="videofile" id="videofile"  @change="handleFileUpload"  multiple>
    <span class="file-cta">
      <span class="file-icon">
        <i class="fas fa-upload"></i>
      </span>
      <span class="file-label">
        Загрузить файл(ы) 
      </span>
    </span>
    <span class="file-name">
        {{totalfiles}} файл(ов)
    </span>
  </label>
</div>

   <br>
   <div v-if="filenames.length" >
     <div class="columns"><div class="column"><b>Файл</b> </div>  <div class="column is-three-fifths"><b>Наименование <i><small>(Произвольный текст)</small></i></b> </div></div>
       <span v-for= '(filename,index) in filenames' > 
           <div class="columns"><div class="column">{{ filename.name }}</div>  <div class="column is-three-fifths"><div class="field"><div class="control has-icons-left has-icons-right"> <input v-model="names[index]"  class="input" type="text"  >  <span class="icon is-small is-left"> <i class="fas fa-film"></i></span></div></div></div></div>
       </span>
   </div>

<hr />
<div class="columns">
  <div class="column">
<div class="field">
  <label class="label">Категория</label>
  <div class="control ">
     <select v-model="categoryId">
        <option v-for="option in this.category_array" :value="option.categoryId" >
         {{option.categoryName}}
        </option>
     </select>
  </div>
 </div>
</div>
<div class="column">
<div class="field">
  <label class="label">Группа</label>
  <div class="control ">
     <select v-model="groupId">
        <option v-for="option in this.group_array" :value="option.groupId" >
         {{option.groupName}}
        </option>
     </select>
  </div>
 </div>
  </div>
</div> 


<p><b>Добавить в Плейлист</b></p>
<p v-for="(option, index) in this.playlist_array" >
    <span v-if="option.name!=''">
    <input type="checkbox" :id="'playlist'+index"  v-model="playlistId[index]">
    <label for="'playlist'+index">{{option.name}}</label>
    </span>
</p>


<hr />
<div v-if="wait"><center><img src="/assets/images/wait.svg"></center></div>
<div class="level-right">
<div  class="field is-grouped">
  <div class="control">
    <button class="button is-link" @click="uploadVideo()"> Сохранить</button>
  </div>
   <div class="control">
    <button class="button is-text" @click="$router.push({ name: 'videos', params: { customerId: currentCustomer,  categoryId: categoryId, scroll: scroll }})" >Отмена</button>
   </div>
</div>
</div>

            </div>
         </div>
         <div class="column"> </div>
       </div>
     </div>
  </div>` })




