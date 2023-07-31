import fbchat
from fbchat import Client
from fbchat.models import *

username = "example@gmail.com"
password = "Password"
# from getpass import getpass
# usernmae = "leonartex15898@gmail.com"
# client = fbchat.Client(usernmae, getpass())
client = Client(username, password)
no_of_friends = int(input("Number of friends:"))
for i in range(no_of_friends):
    name = str(input("Name: "))
    friends = client.searchForUsers(name)
    friend = friends[0]
    msg = str(input("Message: "))
    sent = client.send(fbchat.models.Message(msg),friend.uid)
    if sent:
        print("Message sent successfully")
client.logout()
