import { Circle } from './basic.mjs';
import { Element } from '../dia/Element.mjs';
import { Link } from '../dia/Link.mjs';

export const State = Circle.define('fsa.State', {
    attrs: {
        circle: { 'stroke-width': 3 },
        text: { 'font-weight': '800' }
    }
});

export const StartState = Element.define('fsa.StartState', {
    size: { width: 20, height: 20 },
    attrs: {
        circle: {
            transform: 'translate(10, 10)',
            r: 10,
            fill: '#000000'
        }
    }
}, {
    markup: '<g class="rotatable"><g class="scalable"><circle/></g></g>',
});

export const EndState = Element.define('fsa.EndState', {
    size: { width: 20, height: 20 },
    attrs: {
        '.outer': {
            transform: 'translate(10, 10)',
            r: 10,
            fill: '#ffffff',
            stroke: '#000000'
        },

        '.inner': {
            transform: 'translate(10, 10)',
            r: 6,
            fill: '#000000'
        }
    }
}, {
    markup: '<g class="rotatable"><g class="scalable"><circle class="outer"/><circle class="inner"/></g></g>',
});

export const Arrow = Link.define('fsa.Arrow', {
    attrs: { '.marker-target': { d: 'M 10 0 L 0 5 L 10 10 z' }},
    smooth: true
});


