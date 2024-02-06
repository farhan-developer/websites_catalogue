In this wordpress plugin following tasks are done
In the Front End
1) Added a form which can be implemented on the site using the shortcode [wc_website_form]
2) The form ask the visitor for their name and website URL.
3) When submitted, that information is stored as a post of a custom type called WEBSITES.


And in the Back End:
1) Removed the ability to create new WEBSITES.
2) In the edit screen for a WEBSITE removed all the standard metaboxes (even thepublish one; As the WEBSITES post can not be edited by any way)
3) Added a metabox with a textarea containing the source code of the website at the URL the user provided.
4) Using the new JSON API, allowed to query WEBSITES usinf REST API.
5) Allowed only users with the Administrator or Editor roles to see WEBSITES in the admin. And only Administrators to see the source code of the WEBSITES (Editors can only see
the name).

In the code proper comments are used to easily understand each hook and code that is used in the plugin.
