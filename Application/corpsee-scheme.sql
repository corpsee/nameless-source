CREATE TABLE `tbl_pages` 
(
	`id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
	`name` TEXT NOT NULL UNIQUE,
	`title` TEXT DEFAULT (''),
	`description` TEXT DEFAULT (''),
	`keywords` TEXT DEFAULT ('')
);

CREATE TABLE `tbl_pictures` 
(
	`id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
	`title` TEXT NOT NULL UNIQUE,
	`image` TEXT NOT NULL UNIQUE,
	`description` TEXT DEFAULT (''),
	`create_date` INTEGER DEFAULT(0),
	`post_date` INTEGER DEFAULT(0),
	`modify_date` INTEGER DEFAULT(0)
);

CREATE TABLE `tbl_sites` 
(
	`id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
	`title` TEXT NOT NULL UNIQUE,
	`description` TEXT DEFAULT (''),
	`image` TEXT NOT NULL UNIQUE,
	`post_date` INTEGER DEFAULT(0),
	`modify_date` INTEGER DEFAULT(0)
);

CREATE TABLE `tbl_tags` 
(
	`id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
	`tag` TEXT NOT NULL UNIQUE
);

CREATE TABLE `tbl_pictures_tags`
(
	`id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
	`pictures_id` INTEGER NOT NULL,
	`tags_id` INTEGER NOT NULL
);

CREATE TABLE `tbl_last_modify` 
(
	`id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
	`table` TEXT NOT NULL UNIQUE,
	`modify_date` INTEGER DEFAULT(0)
);

INSERT INTO `tbl_last_modify` VALUES (1,"tbl_pictures", 1352955600);
INSERT INTO `tbl_last_modify` VALUES (2,"tbl_sites", 1352955600);
INSERT INTO `tbl_last_modify` VALUES (3,"tbl_tags", 1352955600);

INSERT INTO `tbl_pages` VALUES (1,"index/index","Corpsee.com. Графика.","Corpsee.com. Графика.","corpsee, графика, graphic");
INSERT INTO `tbl_pages` VALUES (2,"index/bytag","Corpsee.com. Графика по меткам","Corpsee.com. Графика по меткам","corpsee, графика, graphic");
INSERT INTO `tbl_pages` VALUES (3,"index/onetag","Corpsee.com. Графика. Метка","Corpsee.com. Графика. Метка","corpsee, графика, graphic");
INSERT INTO `tbl_pages` VALUES (5,"admin/login","Corpsee.com. Административная панель. ","Corpsee.com. Административная панель. ","corpsee, графика, graphic");
INSERT INTO `tbl_pages` VALUES (6,"admin/error","Corpsee.com. Административная панель. ","Corpsee.com. Административная панель. ","corpsee, графика, graphic");
INSERT INTO `tbl_pages` VALUES (7,"tag/index","Corpsee.com. Административная панель. ","Corpsee.com. Административная панель. ","corpsee, графика, graphic");
INSERT INTO `tbl_pages` VALUES (8,"tag/add","Corpsee.com. Административная панель.","Corpsee.com. Административная панель.","corpsee, графика, graphic");
INSERT INTO `tbl_pages` VALUES (9,"tag/edit","Corpsee.com. Административная панель.","Corpsee.com. Административная панель.","corpsee, графика, graphic");
INSERT INTO `tbl_pages` VALUES (10,"gallery/index","Corpsee.com. Административная панель.","Corpsee.com. Административная панель.","corpsee, графика, graphic");
INSERT INTO `tbl_pages` VALUES (11,"gallery/add","Corpsee.com. Административная панель.","Corpsee.com. Административная панель.","corpsee, графика, graphic");
INSERT INTO `tbl_pages` VALUES (12,"gallery/crop","Corpsee.com. Административная панель.","Corpsee.com. Административная панель.","corpsee, графика, graphic");
INSERT INTO `tbl_pages` VALUES (13,"gallery/result","Corpsee.com. Административная панель.","Corpsee.com. Административная панель.","corpsee, графика, graphic");
INSERT INTO `tbl_pages` VALUES (14,"gallery/edit","Corpsee.com. Административная панель.","Corpsee.com. Административная панель.","corpsee, графика, graphic");
INSERT INTO `tbl_pages` VALUES (15,"gallery/editimage","Corpsee.com. Административная панель.","Corpsee.com. Административная панель.","corpsee, графика, graphic");

INSERT INTO `tbl_pictures` VALUES (1,"#001","i001","#001",1167631200,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (2,"#002","i002","#002",1199167200,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (3,"#003","i003","#003",1199167200,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (4,"#004","i004","#004",1199167200,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (5,"#010","i010","#010",1251781200,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (6,"#011","i011","#011",1251781200,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (7,"#012","i012","#012",1251781200,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (8,"#013","i013","#013",1254373200,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (9,"#1014","i1014","#1014",1262325600,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (10,"#1013","i1013","#1013",1262325600,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (11,"#1012","i1012","#1012",1262325600,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (12,"#1010","i1010","#1010",1262325600,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (13,"#1006","i1006","#1006",1283317200,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (14,"#1000","i1000","#1000",1270098000,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (15,"#1001","i1001","#1001",1277960400,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (16,"#1002","i1002","#1002",1277960400,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (17,"#1003","i1003","#1003",1283317200,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (18,"Char","char","Char",1280638800,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (19,"Nihil","nihil","Nihil",1287723600,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (20,"Friends only","friends_only","Friends only",1287723600,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (21,"Finis","finis","Finis",1292565600,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (22,"#1004","i1004","#1004",1283317200,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (23,"#1005","i1005","#1005",1283317200,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (24,"#1007","i1007","#1007",1288591200,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (25,"#1008","i1008","#1008",1288591200,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (26,"ThornsFlesh2","thornsflesh","ThornsFlesh",1309842000,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (27,"Thorns","thorns","Thorns",1306904400,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (28,"Heads","heads","Heads",1306904400,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (29,"#1011","i1011","#1011",1293861600,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (30,"Lillies","lillies","Lillies",1312779600,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (31,"Skull","skull","Skull",1322110800,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (32,"Mask","mask","Mask",1317445200,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (33,"Zombie","zomby","Zombie",1318222800,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (34,"Zombie II","zomby2","Zombie II",1317445200,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (35,"Thorns 1211","thorns2711","Thorns 1211",1322370000,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (36,"Observer","observer","Observer",1337230800,1352955600,1352955600);
INSERT INTO `tbl_pictures` VALUES (37,"Iggdrasil","iggdrasil","Iggdrasil",1346130000,1352955600,1352955600);

INSERT INTO `tbl_tags` VALUES (1,"corpsee");
INSERT INTO `tbl_tags` VALUES (2,"thorns");
INSERT INTO `tbl_tags` VALUES (3,"meat");
INSERT INTO `tbl_tags` VALUES (4,"vector");
INSERT INTO `tbl_tags` VALUES (5,"mask");
INSERT INTO `tbl_tags` VALUES (6,"skull");
INSERT INTO `tbl_tags` VALUES (7,"finis");
INSERT INTO `tbl_tags` VALUES (8,"observer");
INSERT INTO `tbl_tags` VALUES (9,"iggdrasil");
INSERT INTO `tbl_tags` VALUES (10,"nordic");

INSERT INTO `tbl_pictures_tags` VALUES (1,1,1);
INSERT INTO `tbl_pictures_tags` VALUES (2,2,1);
INSERT INTO `tbl_pictures_tags` VALUES (3,3,1);
INSERT INTO `tbl_pictures_tags` VALUES (4,4,1);
INSERT INTO `tbl_pictures_tags` VALUES (5,5,1);
INSERT INTO `tbl_pictures_tags` VALUES (6,6,1);
INSERT INTO `tbl_pictures_tags` VALUES (7,7,1);
INSERT INTO `tbl_pictures_tags` VALUES (8,8,1);
INSERT INTO `tbl_pictures_tags` VALUES (9,9,1);
INSERT INTO `tbl_pictures_tags` VALUES (10,10,1);
INSERT INTO `tbl_pictures_tags` VALUES (11,11,1);
INSERT INTO `tbl_pictures_tags` VALUES (12,12,1);
INSERT INTO `tbl_pictures_tags` VALUES (13,13,1);
INSERT INTO `tbl_pictures_tags` VALUES (14,14,1);
INSERT INTO `tbl_pictures_tags` VALUES (15,15,1);
INSERT INTO `tbl_pictures_tags` VALUES (16,16,1);
INSERT INTO `tbl_pictures_tags` VALUES (17,17,1);
INSERT INTO `tbl_pictures_tags` VALUES (18,18,1);
INSERT INTO `tbl_pictures_tags` VALUES (19,19,1);
INSERT INTO `tbl_pictures_tags` VALUES (20,20,1);
INSERT INTO `tbl_pictures_tags` VALUES (21,21,1);
INSERT INTO `tbl_pictures_tags` VALUES (22,22,1);
INSERT INTO `tbl_pictures_tags` VALUES (23,23,1);
INSERT INTO `tbl_pictures_tags` VALUES (24,24,1);
INSERT INTO `tbl_pictures_tags` VALUES (25,25,1);
INSERT INTO `tbl_pictures_tags` VALUES (26,26,1);
INSERT INTO `tbl_pictures_tags` VALUES (27,27,1);
INSERT INTO `tbl_pictures_tags` VALUES (28,28,1);
INSERT INTO `tbl_pictures_tags` VALUES (29,29,1);
INSERT INTO `tbl_pictures_tags` VALUES (30,30,1);
INSERT INTO `tbl_pictures_tags` VALUES (31,31,1);
INSERT INTO `tbl_pictures_tags` VALUES (32,32,1);
INSERT INTO `tbl_pictures_tags` VALUES (33,33,1);
INSERT INTO `tbl_pictures_tags` VALUES (34,34,1);
INSERT INTO `tbl_pictures_tags` VALUES (35,35,1);
INSERT INTO `tbl_pictures_tags` VALUES (36,36,1);
INSERT INTO `tbl_pictures_tags` VALUES (37,37,1);

INSERT INTO `tbl_pictures_tags` VALUES (38,35,2);
INSERT INTO `tbl_pictures_tags` VALUES (39,26,2);
INSERT INTO `tbl_pictures_tags` VALUES (40,30,2);
INSERT INTO `tbl_pictures_tags` VALUES (41,27,2);
INSERT INTO `tbl_pictures_tags` VALUES (42,29,2);
INSERT INTO `tbl_pictures_tags` VALUES (43,24,2);
INSERT INTO `tbl_pictures_tags` VALUES (44,21,2);
INSERT INTO `tbl_pictures_tags` VALUES (45,15,2);
INSERT INTO `tbl_pictures_tags` VALUES (46,16,2);
INSERT INTO `tbl_pictures_tags` VALUES (47,8,2);

INSERT INTO `tbl_pictures_tags` VALUES (48,27,3);
INSERT INTO `tbl_pictures_tags` VALUES (49,29,3);
INSERT INTO `tbl_pictures_tags` VALUES (50,24,3);
INSERT INTO `tbl_pictures_tags` VALUES (51,15,3);
INSERT INTO `tbl_pictures_tags` VALUES (52,5,3);

INSERT INTO `tbl_pictures_tags` VALUES (53,21,4);
INSERT INTO `tbl_pictures_tags` VALUES (54,20,4);
INSERT INTO `tbl_pictures_tags` VALUES (55,19,4);
INSERT INTO `tbl_pictures_tags` VALUES (56,18,4);

INSERT INTO `tbl_pictures_tags` VALUES (57,2,5);
INSERT INTO `tbl_pictures_tags` VALUES (58,10,5);
INSERT INTO `tbl_pictures_tags` VALUES (59,13,5);
INSERT INTO `tbl_pictures_tags` VALUES (60,22,5);
INSERT INTO `tbl_pictures_tags` VALUES (70,25,5);
INSERT INTO `tbl_pictures_tags` VALUES (71,28,5);
INSERT INTO `tbl_pictures_tags` VALUES (72,32,5);
INSERT INTO `tbl_pictures_tags` VALUES (73,34,5);
INSERT INTO `tbl_pictures_tags` VALUES (74,33,5);
INSERT INTO `tbl_pictures_tags` VALUES (75,31,5);

INSERT INTO `tbl_pictures_tags` VALUES (76,31,6);
INSERT INTO `tbl_pictures_tags` VALUES (77,25,6);
INSERT INTO `tbl_pictures_tags` VALUES (78,22,6);
INSERT INTO `tbl_pictures_tags` VALUES (79,4,6);
INSERT INTO `tbl_pictures_tags` VALUES (80,36,6);

INSERT INTO `tbl_pictures_tags` VALUES (81,21,7);

INSERT INTO `tbl_pictures_tags` VALUES (82,36,8);

INSERT INTO `tbl_pictures_tags` VALUES (83,37,9);

INSERT INTO `tbl_pictures_tags` VALUES (84,37,10);