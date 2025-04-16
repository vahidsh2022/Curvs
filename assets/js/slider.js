function updateRangePosition(rangeId, value,firstTime = false) {
    let rangePoint = document.getElementById(rangeId + 'Point');
    let rangeLabel = document.getElementById(rangeId + 'Label');
    let container = document.getElementById(rangeId + 'RangeContainer');

    if(firstTime) {
        value = parseFloat(rangePoint.getAttribute('data-value')) ?? value
    }

    let maxValue = 100;
    let percentage = (value / maxValue) * 100;

    rangePoint.style.left = `calc(${percentage}% - 6px)`;
    rangeLabel.textContent = value;
    rangePoint.setAttribute('data-value', value);

    const inputId = rangeId + '_input'
    let input = document.getElementById(inputId);
    if( input) {
        input.value = value;
    } else {
        input = document.createElement('input');
        input.id = inputId;
        input.name = rangePoint.getAttribute('data-input-name') ?? rangeId;
        input.value = value;
        input.style.visibility = 'hidden';
        container.appendChild(input);
    }
}

function handlePointDrag(event, rangeId) {
    let rangePoint = document.getElementById(rangeId + 'Point');
    let container = document.getElementById(rangeId + 'RangeContainer');
    let containerRect = container.getBoundingClientRect();

    let offsetX = event.clientX - containerRect.left;
    let maxWidth = containerRect.width;
    let newValue = Math.round((offsetX / maxWidth) * 100);

    if (newValue < 0) newValue = 0;
    if (newValue > 100) newValue = 100;

    updateRangePosition(rangeId, newValue);
}

document.querySelectorAll('.range-point').forEach(function(point) {
    point.addEventListener('mousedown', function(event) {
        event.preventDefault();

        let rangeId = point.id.replace('Point', '');

        function onMouseMove(event) {
            handlePointDrag(event, rangeId);
        }

        function onMouseUp() {
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);
        }

        document.addEventListener('mousemove', onMouseMove);
        document.addEventListener('mouseup', onMouseUp);
    });
});


document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('div.range-point').forEach(elm => updateRangePosition(elm.id.replace('Point',''), 0,true))
});
