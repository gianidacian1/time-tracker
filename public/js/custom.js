/*  Add project to UI */
function addTask() {
    let name = $('#task_input').val()

    //save to db
    $.ajax({
        url: '/task/store',
        method: 'post',
        data: {name: name},
        dataType: "JSON",
        success: function (result) {
            let data = result.data
            //append html
            appendTask(data);
           
        }
    });
}

function appendTask(data) {

    const id   = data.id
    const name = data.name

    // Create markup
    const html = `
    <tr class="tasks" id="task_${id}">
        <td id="task_id_${id}">${name}</td>
        <td id="task_status_${id}">Backlog</td>
        <td>
            <div id="timer_${id}" data-intervalId="">
                <p class="timer-text">
                    <span class="hours">00</span>:<span class="minutes">00</span>:<span class="seconds">00</span>
                </p>
            </div>
        </td>
        <td>
            <button class="btn btn-success radius-50p" id="start_${id}" onclick="startTimer(${id})">
                <i class="fa fa-play"></i>
            </button>
            <button class="btn btn-success radius-50p d-none" id="stop_${id}" onclick="stopTimer(${id})">
                <i class="fa fa-stop"></i>
            </button>
            <button class="btn btn-danger radius-50p"><i class="fa fa-times"></i></button>
        </td>
        
    </tr>
    `;
    // Insert the HTML into the DOM
    $('#tasks-body').append(html);
    clearField();
    $('#start_'+id).trigger('click');
}

/* Delete task */
function deleteTask(task_id) {

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/task/delete',
                method: 'post',
                data: {task_id: task_id},
                success: function (result) {
                    if(result.success) {
                        $('#task_'+task_id).remove();
                        // Swal.fire(
                        //     'Deleted!',
                        //     'Your file has been deleted.',
                        //     'success'
                        // )
                    }
                }
            });
        }
    })
}

/* Start Timer */
function startTimer(task_id, time) {
    startActions(task_id);
   
    let postData = {
        task_id: task_id,
        action: "start-time"
    }
    //update to db
    $.ajax({
        url: '/task/update-time',
        method: 'post',
        data: postData,
        success: function (result) {
            console.log(result);
        }
    });
    
    let seconds =  0;

    let timeInterval = setInterval(() => {
        seconds++;

        let time = formatTime(seconds);

        // let timer = `
        //     <p class="timer-text" >
        //         <span class="hours">00</span>:<span class="minutes">${min}</span>:<span class="seconds">${sec}</span>
        //     </p>
        // `;
        
        // $('#timer_'+task_id).empty().append(timer);
        let name = $('#task_'+task_id).find('td').eq(1).text();

        let timer = `
            <div>${name}</div>
                <div class="timer-text" id="general_timer_text">
                <span><i class="fa fa-clock-o"></i></span>
                <span class="hours bold">${time.hours}</span>:<span class="minutes bold">${time.min}</span>:<span class="seconds">${time.sec}</span>
            </div>
           `;

        $('#general_timer').empty().append(timer);
    }, 1000);

    // $('#timer_'+task_id).data('intervalId', timeInterval);
    $('#timer_'+task_id).attr('data-intervalId', timeInterval);
    $('#general_timer').attr('data-intervalId', timeInterval);

    
    
}

function formatTime(seconds)
{
    let sec   = (seconds % 60).toString().padStart(2, '0');
    let min   = (parseInt(seconds / 60) % 60).toString().padStart(2, '0');
    let hours = (parseInt(seconds / 3600)).toString().padStart(2, '0');
    let time  = {sec, min, hours}
   
    return time
}
/* Stop timer and clear the intervalId */
function stopTimer(task_id) {
    let intervalId = $('#timer_'+task_id).data('intervalid');
    // let dt = moment();
    // end_date: dt.format('YYYY-MM-DD h:mm:ss'),
    let postData = {
        task_id: task_id,
        
        action: "stop-time"
    }
    //update to db
    $.ajax({
        url: '/task/update-time',
        method: 'post',
        data: postData,
        success: function (res) {
            time = res.total_time
            updateTaskTotalTime(task_id, time);
        }
    });
    clearInterval(intervalId);
    resetTimer(task_id);
    stopActions(task_id);
    
    
}

/* Helpers */

function updateTaskTotalTime(task_id, time) {
    t = formatTime(time)
    const html = `
            <p class="timer-text"><span class="hours">${t.hours}</span>:<span class="minutes">${t.min}</span>:<span class="seconds">${t.sec}</span></p>
    `;

    $('#timer_'+task_id).empty().append(html);
}

function changeStatus(task_id) {
    let postData = {
        task_id: task_id,
        status: "Done"
    }
    //update to db
    $.ajax({
        url: '/task/update',
        method: 'post',
        data: postData,
        success: function (res) {
           console.log(res);
        }
    });
}
/* Clear input */
function clearField() {
    $('#task_input').val('');
}

/* Toggle "start/stop" buttons */
function startActions(task_id) {
    $('#start_'+task_id).addClass('d-none');
    $('#stop_'+task_id).removeClass('d-none');
}

function stopActions(task_id) {
    $('#start_'+task_id).removeClass('d-none');
    $('#stop_'+task_id).addClass('d-none');
}

/* Reset timer and clear intervalId */
function resetTimer(task_id)
{
    // $('#timer_'+task_id).attr('data-intervalid', '');
    $('#general_timer').attr('data-intervalid', '');

    let timer = `
                <span><i class="fa fa-clock"></i></span> <span class="hours bold">00</span>:<span class="minutes bold">00</span>:<span class="seconds">00</span>
    `;

    // $('#timer_'+task_id).empty().append(timer);
    $('#general_timer_text').empty().append(timer);
}

$(document).ready(function () {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var table = $('#tasks-table').DataTable({});
 
});

window.onload = function () {

};
