@extends('layout.userlayout')

@section('content')
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* CSS Enhancements */

.main-content {
    padding: 8%;
}

label {
  font-size: 1rem;
  font-weight: bold;
  color: #365AC2;
  margin-bottom: 5px;
}

input {
  width: 100%;
  padding: 10px;
  margin-bottom: 5%;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 1rem;
}

.form-container {
    width: 40%;
    border-radius: 20px;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-top: 2%;
    margin-left: 27%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding-left: 3%;
    padding-top: 3%;
}

.form-group {
    margin-top: 0px;
}


.form-group input {
    width: 83%;
    padding: 15px;
    margin-top: 10px;
    border-radius: 8px;
    border: 2px solid #365AC2; /* Menambahkan border */
}

.form-group textarea {
    width: 83%;
    height: 70px;
    padding: 15px;
    margin-top: 10px;
    margin-bottom: 2%;
    border-radius: 8px;
    border: 2px solid #365AC2;
    font-family: 'Poppins', sans-serif;
    font-size: 1rem;
}


.form-container button[type="submit"]{
    float: right;
    margin-right: 11%;
    margin-top: 2%;
    margin-bottom: 7%; /* Right margin for submit button */
    font-size: 0.850rem; /* Adjust font size for submit button */
    padding: 10px 20px; /* Add padding for submit button */
    background-color: #365AC2; /* Set background color */
    color: white; /* White text */
    border: none; /* Remove border */
    border-radius: 4px; /* Rounded corners */
    cursor: pointer; /* Pointer cursor */
}

.how {
    display: inline-flex;
    align-items: center;
    width: 50%;
}

.how a{
    text-decoration: underline;
    cursor: pointer;
    width: 100%;
    color: #365AC2;
}

.how a:hover {
    color: #2a4494;
}

/* Modal container */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: #25419750;
}

/* Modal content */
.modal-content {
    background-color: #fff;
    margin: 9% auto;
    padding: 30px;
    width: 65%;
    max-width: 30%;
    max-height: 55%;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(27, 24, 124, 0.1);

}

/* Close button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #365AC2;
    text-decoration: none;
    cursor: pointer;
}

h3{
    font-size: 2rem;
    color: #365AC2;
    margin-bottom: 6%;
    padding-left: 5%;

}

/* Flexbox layout for step and instruction */
.steps {
    margin-top: 5%;
    margin-bottom: 5%;
    padding-left: 5%;
    padding-right: 5%;
}

.step {
    display: flex;
    align-items: flex-start;
    margin-bottom: 15px;
}

.step-number {
    background-color: #365AC2;
    color: #fff;
    font-size: 1rem;
    font-weight: bold;
    width: 45px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-right: 15px;
}

.step-instruction {
    margin: 0;
    line-height: 1.5;
    font-size: 1rem;
}


.breadcrumb {
    display: flex;
    list-style: none;
    padding: 0;
    margin-bottom: 5%;
}

.breadcrumb-item:hover{
    text-decoration: underline solid #365AC2;
}

.breadcrumb-item {
    position: relative;
    font-size: 16px;
}

.breadcrumb-item a {
    color: #3200af;
    text-decoration: none;
}

.breadcrumb-item.active {
    color: #3200af;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 3px; /* Adjust the spacing between items */
    width: 9px;
    height: 9px;
    border-top: 2px solid #3200af;
    border-right: 2px solid #3200af;
    transform: rotate(45deg) translateY(-50%);

}

.breadcrumb-item:not(:first-child) {
    padding-left: 30px; /* Adjust this value based on diagonal size */
}


    @media (max-width: 768px) {



    /* Form container adjustments */
    .form-container {
        width: 90%;
        margin: 20px auto;
        padding: 20px;
        font-size: 1rem;
    }

    .form-group input,
    .form-group textarea {
        width: 90%;
        padding: 10px;
        font-size: 0.8rem;
    }

    /* Modal adjustments */
    .modal-content {
        max-width: 65%;
        height: auto;
        padding: 20px;
        font-size: 0.7rem;
    }

    .modal-content h3{
        font-size: 1.5rem;
        margin-bottom: 10%;
    }

    .step-number {
        width: 70px;
        height: 25px;
        font-size: 0.8rem;
    }
    .steps p {
        font-size: 0.8rem;
    }

    .modal {
        align-items: center;
        padding-top: 10%;
    }
    .how {
        font-size: 0.7rem;
    }

    .form-container label {
        font-size: 0.9rem;
        margin-top: 2%;
    }
    .form-container button[type="submit"] {
        margin-right: 5%;
    }

    /* Breadcrumb adjustments */
    .breadcrumb-item {
        font-size: 0.6rem;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        left: 5px;
        width: 7px;
        height: 7px;
    }


    }


    </style>
</head>
    <div id="main-content" class="main-content">

         <!-- Content Header (Page header) -->
			<br>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb" style="background-color: transparent;">
					<li class="breadcrumb-item"><a href="{{ route('organization') }}" style="color: #3200af;">Organization</a></li>
					<li class="breadcrumb-item active" aria-current="page" style="color: #3200af;">Create Organization</li>
				</ol>
			</nav>
          <!-- Form Section -->
          <div class="form-container">
            <!-- Form for creating organization -->
            <form action="{{ route('addorganization') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="organization_name" class="form-label">Organization Name</label>
                    <input type="text" name="organization_name" id="organization_name" class="form-input" placeholder="Enter Organization Name" required>
                </div>
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea type="text" name="description" id="description" class="form-input" placeholder="Enter Organization Description" required></textarea>
                </div>

                <input type="hidden" name="access_token_status" value="{{ session('access_token')}}">

                <div class="form-group">
                    <button type="submit" class="btn-submit">Create</button>
                </div>

                <div class="how">
                   <a onclick="openModal()">  need a help?</a>
                </div>

            </form>
        </div>

        <!-- Modal Structure -->
        <div id="stepModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <div class="modal-body">
                    <h3>How to create new organization?</h3>
                    <div class="steps">
                        <div class="step">
                            <div class="step-number">1</div>
                            <p class="step-instruction">Insert name and description for your organization, and click "create" button.</p>
                        </div>
                        <div class="step">
                            <div class="step-number">2</div>
                            <p class="step-instruction">We will send an verification email for your organization, check your inbox!</p>
                        </div>
                        <div class="step">
                            <div class="step-number">3</div>
                            <p class="step-instruction">Verification your organization, and new organization is created successful!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        // Get the modal
        var modal = document.getElementById("stepModal");

        // Open the modal
        function openModal() {
            modal.style.display = "block";
        }

        // Close the modal
        function closeModal() {
            modal.style.display = "none";
        }

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById("sidebar");
            var mainContent = document.getElementById("main-content");

            if (sidebar.style.left === "0px") {
                sidebar.style.left = "-270px";
                mainContent.style.marginLeft = "10%";
            } else {
                sidebar.style.left = "0px";
                mainContent.style.marginLeft = "19%";
            }
        }
    </script>

</div>
@endsection
