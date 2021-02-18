let myNums = [1, 2, 3];
let moreNums = [2,3,5];

/*
let sum = myNums.reduce(function(a, b) {
    return a + b;
}, 0);

console.log(sum);
*/

let adding = myNums.reduce((a,b) => (a+b),0);

console.log(adding);