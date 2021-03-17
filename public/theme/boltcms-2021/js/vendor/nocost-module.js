import {annotate} from 'https://unpkg.com/rough-notation?module';

const n1 = document.querySelector('#free');
const n2 = document.querySelector('#free-2');

const a1 = annotate(n1, {type: 'underline', color: 'blue'});
const a2 = annotate(n2, {type: 'circle', color: 'red', padding: 28});

a1.show();
a2.show();

