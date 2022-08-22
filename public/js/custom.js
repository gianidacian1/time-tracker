/*  Add project to UI */
function addTask() {
    let name = $('#task_input').val()
    
    //save to db
    $.ajax({
        url: '/task/store',
        method: 'post',
        data: {name: name},
        dataType: "JSON",
        success: function (res) {
            if(res.errors) {
                const errors = res.errors;

                let html = '<ul>';

                Object.values(errors).forEach(val => {
                    html = html + '<li>' + val + '</li>';
                  });
                html = html + '</ul>';
                
                alertify.error(html);

                // Swal.fire({
                //     position: 'top-end',
                //     icon: 'error',
                //     title: html,
                //     showConfirmButton: false,
                //     timer: 1500
                //   })
                return false;
            }
            
            $('#start').addClass('d-none');
            $('#stop').removeClass('d-none');
            let data = res.data
            $('#current_task').val(data.id);
            //append html
            startTimer(data.id);
            if(res.new) {
                appendTask(data);
            }
           
        }
    }).fail(function (res) {
        console.log(res.errors);
    });
}

function appendTask(data) {

    const id   = data.id
    const name = data.name

    const tr = `
            <tr id="task_${data.id}">
                <td>
                    <input type="checkbox" name="" class="" id="check" onclick="changeStatus(${data.id})">
                </td>
                <td>${data.name}</td>
                <td>${data.status}</td>
                <td>
                    <div id="timer_${data.id}" data-intervalid="">
                        <p class="timer-text"><span class="hours">00</span>:<span class="minutes">00</span>:<span class="seconds">00</span></p>
                    </div>
                    
                </td>
                <td>
                    <button class="btn btn-stop radius-50p" onclick="deleteTask(${data.id})"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </td>
            </tr>
        `;
        $('#tasks-body').append(tr);
    clearField();
    // startTimer(id);
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

    let dt = moment();
    let postData = {
        task_id: task_id,
        start_date: dt.format('YYYY-MM-DD h:mm:ss'),
        action: "start-time"
    }
    
    
    let seconds =  0;
   
    let timeInterval = setInterval(() => {
        seconds++;

        let time = formatTime(seconds);
       
        let name = $('#task_'+task_id).find('td').eq(1).text();

        let timer = `
                    <div class="timer-text" id="general_timer_text">
                        <span class="hours ">${time.hours}</span>:<span class="minutes ">${time.min}</span>:<span class="seconds">${time.sec}</span>
                    </div>
           `;

        $('#general_timer').empty().append(timer);
    }, 1000);
        //update to db
        $.ajax({
            url: '/task/update-time',
            method: 'post',
            data: postData,
            success: function (result) {
                
            }
        });
    
        $('#time_interval').val(timeInterval);
    
    
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
function stopTimer() {
    let task_id = $('#current_task').val();

    $('#start').removeClass('d-none');
    $('#stop').addClass('d-none');
    let intervalId = $('#time_interval').val();;
    let dt = moment();
    let postData = {
        task_id: task_id,
        end_date: dt.format('YYYY-MM-DD h:mm:ss'),
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
    clearField();
    alertify.success("Time added.");
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
                <span><i class="fa fa-clock"></i></span> <span class="hours ">00</span>:<span class="minutes ">00</span>:<span class="seconds">00</span>
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
    // var table = $('#tasks-table').DataTable({});
 
});

window.onload = function () {

};

