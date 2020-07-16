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




### CODE from coderuner type
```python
import subprocess, sys, urllib.request
import urllib.parse
import json

__student_answer__ = """{{ STUDENT_ANSWER | e('py') }}"""
#url = 'https://validator-nu.herokuapp.com/?out=json&lang=ptBR'
url = 'https://validator.w3.org/nu/?out=json&lang=ptBR'
data = __student_answer__

req = urllib.request.Request(url, data.encode('utf-8'))
req.add_header('Content-type', 'text/html; charset=UTF-8')  
response = urllib.request.urlopen(req)
result = response.read()
errors = json.loads(result)

feedback = ""
contError = 0
contWarning = 0
contInfo = 0
listOfErrors = ['The “border” attribute on the “table” element is obsolete. Use CSS instead.']

for error in errors['messages']: 
    eType = str(error['type'])
    eMsg = str(error['message'])
    eLine = error['lastLine']

    if(eMsg not in listOfErrors):

        listOfErrors.append(eMsg)

        feedback = feedback + 'linha ' + str(eLine) + ':' + eMsg + '\\n'

        if eType == 'error':
            contError = contError + 1
        elif eType == 'warning':
            contWarning = contWarning + 1
        else:
            contInfo = contInfo + 1
            
totalErros = contError * 0.1
totalWarning = contWarning * 0.05
notaSugeridaConta = 1 - (totalErros + totalWarning)

print('{"fraction": ' + str(notaSugeridaConta) + ', "got":" ' + feedback + '"}')
```