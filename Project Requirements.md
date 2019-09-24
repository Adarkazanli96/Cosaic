<span style="color: black; font-family: 'Times'; font-size: 12pt;"/>

# <p align="center">Cosaic<br>Team #38</p>
<p align="center">
  <img src="https://github.com/CS-157A-Team-38/CS157A-Team-38/blob/master/Cosaic_icon.png" width=300/>
</p>
<p align="center">Ahmed Darkazanli<br>Albert Ong<br>Anh Phan</p>

## Project Overview
(Ahmed's section)

## System Environement
<p align="center">
  <img src="https://github.com/CS-157A-Team-38/CS157A-Team-38/blob/master/CS_157A_project_proposal_system_environment.png" width=800>
</p>

## Functional Requirements
(Anh's section)

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
