import sys
from skpy import Skype

loggedInUser = Skype("example@gmail.com", "password")
contact = loggedInUser.contacts['live:.cid.8358944c479301f7']
contact.chat.sendMsg("welcome to HTL {}".format(sys.argv[1]))
