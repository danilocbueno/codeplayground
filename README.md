Code Playground - Question type template
----------------------
### Remove question from database
1. First you need to make sure that there are no questions of that type in the database. Do

`SELECT * FROM mdl_question WHERE qtype = 'dragdrop';`

and ensure it returns a empty result set. If not, delete those questions using the Moodle interface.

2. Delete the question/type/dragdrop folder from disc.

3. Remove the database tables that the dragdrop question type creates when it is installed:

`DROP TABLE mdl_question_dragdrop;`

`DROP TABLE mdl_question_dragdrop_hotspot;`

`DROP TABLE mdl_question_dragdrop_media;`

4. Remove the config variable that tell Moodle that this question type is installed, that is

`DELETE FROM mdl_config WHERE name = 'qtype_dragdrop_version'`