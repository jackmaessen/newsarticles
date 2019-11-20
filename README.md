# newsarticles
**A flat file news articles creator written in php**<br />
<br />
It comes with a slider for the newsarticles<br />
Build with TinyMCE editor<br />
Bootstrap grid<br />
Font-awesome icons<br />
No database required!

Create newsarticles and store them in .txt files
The admin can edit and delete messages

How to use?
1. store all files in a directory 
2. open **index.php, slider.php and admin.php**. Inbetween the head tags look for this line:  
```
 <!-- font awesome kit -->
<script src="https://kit.fontawesome.com/*your own kit goes here*.js" crossorigin="anonymous"></script>
```

Get your own kit here: https://fontawesome.com/start
Replace the line in the head tag with your own kit

3. open **admin.php**, look for this line in the head tags:
```
 <!-- API key for TinyMCE -->
<script src="https://cdn.tiny.cloud/1/*your own key goes here*/tinymce/5/tinymce.min.js"></script> 
```

Replace the api key with your own. Get the key here: https://www.tiny.cloud/auth/signup/

4. open **settings.php** and set the variables to your own needs

5. browse to **admin.php** and start writing your first article!

For visitors: 
They use to browse to : **index.php** or **slider.php**

**NB: admin.php is NOT protected with a login. You really should do that if you want to use it in production**
<br /><br />
**DEMO:** <br />
Admin: http://newsarticles1.webprofis.nl/admin.php<br />
Index: http://newsarticles1.webprofis.nl/index.php<br />
Slider: http://newsarticles1.webprofis.nl/slider.php

