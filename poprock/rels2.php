<?php 
    //$artistSpotID = $_GET['artistSpotID'];
    //$artistMBID = $_GET['artistMBID'];
	require_once 'page_pieces/stylesAndScripts.php';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ronnie James Dio network diagram | PopRock</title>
	<?php echo $stylesAndSuch; ?>
</head>

<body>

<div class="container-fluid">
	
<div id="fluidCon"></div> <!-- end of fluidCon -->
    <div class="panel panel-primary">
		<div class="panel-heading">
			<h3 id="name" class="panel-title">Ronnie James Dio network diagram</h3>
		</div>
    <div class="panel-body">
        <svg width="1200" height="900"></svg>

        <script>

        var nodes = [];

        var links = [
            { target: "Ronnie James Dio", source: "Black Sabbath", strength: 0.7 },
            { target: "Ronnie James Dio", source: "Rainbow", strength: 0.7 },
            { target: "Ronnie James Dio", source: "Dio", strength: 0.7 },
            { target: "Ronnie James Dio", source: "Heaven & Hell", strength: 0.7 },
            { target: "Ronnie James Dio", source: "Roger Glover and Friends", strength: 0.7 },
        ];


        /*
        function getNeighbors(node) {
        return links.reduce(function (neighbors, link) {
            if (link.target.id === node.id) {
                neighbors.push(link.source.id)
            } else if (link.source.id === node.id) {
                neighbors.push(link.target.id)
            }
            return neighbors
            },
            [node.id]
        )
        }

        function isNeighborLink(node, link) {
        return link.target.id === node.id || link.source.id === node.id
        }
        */
        function initNodeColor(node) {
        if (node.level === 1) {
            return 'gray'
        }
        if (node.level === 2) {
            return 'red'
        }
        if (node.level === 3) {
            return 'magenta'
        }
        if (node.level === 4) {
            return 'blue'
        }
        if (node.level === 5) {
            return 'green'
        }
        if (node.level === 6) {
            return 'orange'
        }
        }
        /*
        function getNodeColor(node, neighbors) {
        if (Array.isArray(neighbors) && neighbors.indexOf(node.id) > -1) {
            return node.level === 1 ? 'blue' : 'green'
        }
        return node.level === 1 ? 'red' : 'gray'
        }

        function getLinkColor(node, link) {
        return isNeighborLink(node, link) ? 'green' : '#E5E5E5'
        }

        function getTextColor(node, neighbors) {
        return Array.isArray(neighbors) && neighbors.indexOf(node.id) > -1 ? 'green' : 'black'
        }
        */

        var width = 1200
        var height = 1200

        var svg = d3.select('svg')

        svg.attr('width', width).attr('height', height)

        // simulation setup with all forces
        var linkForce = d3
        .forceLink()
        .id(function (link) { return link.id })
        .strength(function (link) { return link.strength })

        var simulation = d3
        .forceSimulation()
        .force('link', linkForce)
        .force('charge', d3.forceManyBody().strength(-600))
        .force('center', d3.forceCenter(width / 2, height / 2))

        var dragDrop = d3.drag().on('start', function (node) {
        node.fx = node.x
        node.fy = node.y
        }).on('drag', function (node) {
        simulation.alphaTarget(0.7).restart()
        node.fx = d3.event.x
        node.fy = d3.event.y
        }).on('end', function (node) {
        if (!d3.event.active) {
            simulation.alphaTarget(0)
        }
        node.fx = null
        node.fy = null
        })

        /*
        function selectNode(selectedNode) {
        var neighbors = getNeighbors(selectedNode)
        // we modify the styles to highlight selected nodes
        nodeElements.attr('fill', function (node) { return getNodeColor(node) })
        textElements.attr('fill', function (node) { return getTextColor(node, neighbors) })
        linkElements.attr('stroke', function (link) { return getLinkColor(selectedNode, link) })
        }
        */

        var linkElements = svg.append("g")
        .attr("class", "links")
        .selectAll("line")
        .data(links)
        .enter().append("line")
            .attr("stroke-width", 1)
            .attr("stroke", "rgba(250, 250, 250, 0.2)")

        var nodeElements = svg.append("g")
        .attr("class", "nodes")
        .selectAll("circle")
        .data(nodes)
        .enter().append("circle")
            .attr("r", 10)
            .attr("fill", initNodeColor)
            .call(dragDrop)
            //.on('click', selectNode)

        var textElements = svg.append("g")
        .attr("class", "texts")
        .selectAll("text")
        .data(nodes)
        .enter().append("text")
            .text(function (node) { return  node.label })
            .attr("font-size", 15)
            .attr("dx", 15)
            .attr("dy", 4)

        /**/
        simulation.nodes(nodes).on('tick', () => {
        nodeElements
            .attr('cx', function (node) { return node.x })
            .attr('cy', function (node) { return node.y })  
        textElements
            .attr('x', function (node) { return node.x })
            .attr('y', function (node) { return node.y })
        linkElements
            .attr('x1', function (link) { return link.source.x })
            .attr('y1', function (link) { return link.source.y })
            .attr('x2', function (link) { return link.target.x })
            .attr('y2', function (link) { return link.target.y })
        })


        simulation.force("link").links(links)

        </script>
            </div>
		</div> <!-- panel body -->
	</div> <!-- close Panel Primary -->

</div> <!-- close container-fluid -->


	



<?php echo $scriptsAndSuch; ?>

<script src="https://www.roxorsoxor.com/poprock/page_pieces/navbarIndex.js"></script>
</body>

</html>