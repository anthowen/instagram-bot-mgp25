# Instagram Bot

## Build
	composer install

## Run
	Open your console, and run this command

		php index.php

## Report
	Once bot action has finished, open index.html in your browser, it contains report table of action results.
	
Added screenshot picture.

## CSV explanation
	
### users.csv
	Contains user credientials

	schema: username,password

	ex:
		blizzardsolution67,testabc123
		morganbigman,testabc1234
		ouracc123,ouracc123

### actions.csv
	Actions list

	schema: action_type,source_account,dest_account,args

	ex:
		follow,blizzardsolution67,fcbarcelona,null
		like,blizzardsolution67,lukamodric10,2			( likes Dest's recent 2 posts)
