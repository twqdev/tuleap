<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id$

require "pre.php";    
$HTML->header(array(title=>"Exports Available"));
?>
<P>CodeX data is exported in a variety of standard formats. Many of
the export URLs can also accept form/get data to customize the output. All
data generated by these pages is realtime.
<P><B>News Data</B>
<UL>
<LI><A href="rss_sfnews.php">CodeX Front Page/Project News</A>
(<A href="http://my.netscape.com/publish/formats/rss-spec-0.91.html">RSS 0.91</A>,
<A href="http://my.netscape.com/publish/formats/rss-0.91.dtd">&lt;rss-0.91.dtd&gt;</A>)
<LI><A href="rss_sfnewreleases.php">CodeX New Releases</A>
(<A href="http://my.netscape.com/publish/formats/rss-spec-0.91.html">RSS 0.91</A>,
<A href="http://my.netscape.com/publish/formats/rss-0.91.dtd">&lt;rss-0.91.dtd&gt;</A>)
</UL>
<P><B>Listings</B>
<UL>
<LI><A href="rss_sfprojects.php?type=rss">CodeX Full Project Listing - RSS Format</A>
(<A href="http://my.netscape.com/publish/formats/rss-spec-0.91.html">RSS 0.91</A>,
<A href="http://my.netscape.com/publish/formats/rss-0.91.dtd">&lt;rss-0.91.dtd&gt;</A>)
<LI><A href="rss_sfprojects.php?type=csv">CodeX Full Project Listing - CSV Format</A>
</UL>
<P><B>Project Information</B>
<UL>
<LI><A href="nitf_sfforums.php">Project Forums</A>, ?group_id required
(<A href="sf_forum_0.1.dtd.txt">&lt;sf_forum_0.1.dtd&gt;</A>)
</UL>
<?php
$HTML->footer(array());

?>
