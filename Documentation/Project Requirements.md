<span style="color: black; font-family: 'Times'; font-size: 12pt;"/>

# <p align="center">Cosaic<br>Team #38</p>
<p align="center">
  <img src="https://github.com/CS-157A-Team-38/CS157A-Team-38/blob/master/Cosaic_icon.png" width=300/>
</p>
<p align="center">Ahmed Darkazanli<br>Albert Ong<br>Anh Phan</p>

## Project Overview
(Ahmed's section)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cosaic is a social networking application that allows users to share their daily lives through online image posts. When users login to Cosaic, they will be able to view their previous posts on their own profile page as well as update their status, post pictures, and add captions and hashtags. Additionally, Cosaic will allow users to connect with other users by searching for their profile name and viewing their posts. Cosaic is designed to be a fun, casual platform that allows users to share their story with the world and view the stories of others.

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The stakeholders of this project will include the members of the project team (Ahmed Darkazanli, Albert Ong, and Chloe Phan), project manager (Professor Mike Wu), and every user who loves to share their interests and activities and regularly follow their favorite people. The individuals who most enjoy interacting through social media will be the end-users that our application is targeted at. Therefore, our team must ensure that our software is functional, secure, and bug-free in order to provide the best user experience and maintain a positive image of the people working on the project.

## System Environment
<p align="center">
  <img src="https://github.com/CS-157A-Team-38/CS157A-Team-38/blob/master/CS_157A_project_proposal_system_environment.png" width=800>
</p>

#### Hardware and software used:
Apache web server
Mac/PC

#### RDBMS:
MySql

#### Application Languages:
HTML/CSS, PHP, SQL

## Functional Requirements
(Anh's section)

### Describe users:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;End users will be people who are interested in using our social media service.

### How users can access the system:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Users will need a PC or laptop to connect to the browser such as Safari, Google Chrome, or Internet Explorer. Users will be required to sign up for a new account or log in if they have already created an account previously with Cosaic. 
Describe each functionality/features, functional processes and I/O(s).

### View profile
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;After users log in their account, they will initially be shown a list of all their posts on the home page. Users will be able to view their profile details, including a summary of posts they have made and their profile picture.

### Create Posts
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;On the top right of the Cosaic’s website, there is an option to create a post. By clicking on the “+” button, a status pop up will appear on the screen and prompt users to enter information. Users can add their information that they want to share in the “caption” box. The message can be anything what’s going on in their lives, what’s changed, or simply how they’re feeling or what they’re thinking. Advanced functionality allows users to also attach pictures to the messages they post. Displaying an interactive visualization makes it easier for viewers to imagine the meaning and background of the messages. Users can post their message on their home page by simply clicking on the “Save” button at the bottom of the table.

### Delete Posts
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Users can delete their posts by clicking the “...” button on the top right of the menu bar to bring up the options menu, then click the “-” symbol next to each post they want to delete. Cosaic will prompt the user to confirm that they want to delete their post. Because their posts cannot be recovered after deleting, they have an option that allows the user to reconsider by clicking cancel or confirm their choice by choosing delete. After deleting the posts, users will see their home page with an updated view.

### Edit Posts  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Users can delete their posts by choosing “...” button on the top right of the menu bar to bring up the options menu, then click the symbol next to the post they want to edit. The table of status will display the current message being edited and a picture, if the post has a picture associated with it. Users have an option to edit the content of their messages or reupload their picture. If users want to change their picture, there is a button “X” what users can click on to delete the picture. After that, users can re-upload a new photo. To successfully edit the chosen post, users will need to click on the “Save” button at the bottom at the table to publish their changes.

### View other users posts    
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Users can search for other people who have an account on Cosaic by inputting another users’ names in the search bar at the middle top of the menu bar and clicking the button search. Because ids on Cosaic are unique for each user and each account, users will immediately be brought to the searched user’s home page. The searched users’ homepage layout is the same style as users’ homepage, but there is no option to Create Posts, Delete Posts, and Edit Posts.


## Non-functional Issues
(Albert's section)

### Graphic User Interface (GUI)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The fundamental graphic user interface (GUI) of Cosaic will consist of HTML files, which contain content such as dividers, buttons, and input fields, combined with CSS files which are used to style the documents for presentation aesthetics. An integrated development environment (IDE), such as Microsoft Visual Studio Code, will be used to write, edit, and maintain these documents. Additionally, graphic design assets, including images, logos, icons, and other branding necessities, will be drawing using a vector based imaging program such as Adobe Illustrator or Inkscape. If further image editing capabilities are required to complete the project, then tools such as Adobe Photoshop and GIMP may be deployed. 

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The user interface of Cosiac will abide by the fundamental graphic design principles of contrast, repetition, alignment, and proximity to ensure and aesthetically pleasing user experience.  The main page will double as the login page and include the Cosaic logo, input fields for a user's email and password, the login button, as well as a button that links to the signup page. The signup page will have a similar layout to the login page, with the most significant distinction being functionality rather than aesthetics. After logging in, a user will be presenting with their user profile page, which will include a search bar, logout button, profile description, post button, and image posts alongside image captions. The logout button will simply return the user to the login screen while the search bar will allow the user to search for the profiles of other users on the platform. The post button will redirect the user to another page that allows them to upload an image and write a caption to said image that will be posted to their own profile. 
  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Because the main feature of Cosaic is the ability to share photos will the public, the majority of the screen will be occupied by the images of image posts while the minority will be occupied by the remaining features. When viewing either their own profile or ther profile of another user, users will be able to scroll down the page and view posts chronologically by earliest post first. The use of repetition will be clearly defined in this aspect of the user interface because image files will be repeated in the same manner while ordered in a vertical fashion. 

#### Example user interface
<p align="center">
  <img src="https://github.com/CS-157A-Team-38/CS157A-Team-38/blob/master/Cosaic_example_user_interface.png" width=700/>
</p>

### Security
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Accounts will be identified by their respective email and passwords, therefore security measures should be implemented to ensure the safety of the platform. One method of security that will be implemented is SQL injection, which will involve filtered data using mysql[i]_real_escape_string() command to ensure that dangerous characters will not be passed into the SQL query. Additional, user input will be sanitized by filtering the user's data by context. Furthermore, passwords will be secured using some type of cryptographic function, such as RIPEMD, and utilize salting, the practice of adding random data before and after user input to confuse attackers. To avoid cross-site scripting (XSS), our project will filter output to the browser through the htmlentities() command. Finally, the project will avoid session fixation and hijacking by regenerating session IDs and by utilizing secure socket layers (SSL). 


### Access Control
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A user's profile along with all images and captions posted to said profile will be visible to other Cosaic users as well as the general public. However, control over an individual user profile will be limited to the user themself and to administrators of the platform – which shall consist of the three members of the development team. The rights restricted by this group will include the ability to add a post to one's profile, delete a post after it has been added, and delete the account along with all associated posts. Administrator accounts will have a higher authority than regular user accounts and therefore actions performed by administrators have priority over those of regular users. The system of access control that has been described is designed to ensure that all users can view the profiles of others, the owner of a user profile will be able to control the content of their own profile, and administrators will have regulatory privileges that supercede the rights of all other users.  
