function TweetTick(ob)
{
	//var container=$('#tweet-container');
	//console.log(containerId);
	var container=$(containerId);
	var lastidx = 0;
	container.html('');
	if (ob.results.length != ob.results_per_page) {
		lastidx = ob.results.length-1;
	}
	else {
		lastidx = ob.results_per_page-1;
	}  	
	$(ob.results).each(function(index,el){
		var css = '';
        if(index == 0){
            css = ' first';
        }
        if(index == lastidx){
            css = ' last';
        }
        var str = ' <div class="tweet '+css+'">\
					<div class="avatar"><a href="http://twitter.com/'+this.from_user+'" target="_blank"><img src="'+this.profile_image_url+'" alt="'+this.from_user+'" /></a></div>\
					<div class="user"><a href="http://twitter.com/'+this.from_user+'" target="_blank">'+this.from_user+'</a></div>\
					<div class="time">'+relativeTime(this.created_at)+'</div>\
					<div class="txt">'+formatTwitString(this.text)+'</div>\
					</div>';
		container.append(str);
	});
	if (jQuery().jScrollPane){
		container.jScrollPane();
	}
}

function formatTwitString(str)
{
	str=' '+str;
	str = str.replace(/((ftp|https?):\/\/([-\w\.]+)+(:\d+)?(\/([\w/_\.]*(\?\S+)?)?)?)/gm,'<a href="$1" target="_blank">$1</a>');
	str = str.replace(/([^\w])\@([\w\-]+)/gm,'$1@<a href="http://twitter.com/$2" target="_blank">$2</a>');
	str = str.replace(/([^\w])\#([\w\-]+)/gm,'$1<a href="http://twitter.com/search?q=%23$2" target="_blank">#$2</a>');
	return str;
}

function relativeTime(pastTime)
{	
	var origStamp = Date.parse(pastTime);
	var curDate = new Date();
	var currentStamp = curDate.getTime();
	
	var difference = parseInt((currentStamp - origStamp)/1000);

	if(difference < 0) return false;

	if(difference <= 5)				return "Just now";
	if(difference <= 20)			return "Seconds ago";
	if(difference <= 60)			return "A minute ago";
	if(difference < 3600)			return parseInt(difference/60)+" minutes ago";
	if(difference <= 1.5*3600) 		return "One hour ago";
	if(difference < 23.5*3600)		return Math.round(difference/3600)+" hours ago";
	if(difference < 1.5*24*3600)	return "One day ago";
	
	var dateArr = pastTime.split(' ');
	return dateArr[4].replace(/\:\d+$/,'')+' '+dateArr[2]+' '+dateArr[1]+(dateArr[3]!=curDate.getFullYear()?' '+dateArr[3]:'');
}