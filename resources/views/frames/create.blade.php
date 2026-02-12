@extends('layouts.main')

@section('page-title', 'Create Frame')

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('frame.index') }}" class="btn btn-secondary float-end">Back to List</a>
        </div>
        <div class="card-body">
            <form action="{{ route('frame.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="mb-3">
                            <label for="frame_name" class="form-label">Frame Name</label>
                            <input type="text" name="frame_name" id="frame_name" class="form-control"
                                placeholder="Enter Frame Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Frame Image</label>
                            <div class="file-input-wrapper">
                                <input type="file" name="image" id="image" class="file-input" accept="image/*" onchange="handleImageSelect(this)">
                                <label for="image" class="file-input-label">
                                    <i class="fas fa-cloud-upload-alt file-input-icon"></i>
                                    <span class="file-input-text">Choose Image file or drag and drop</span>
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="data" class="form-label">Frame Code</label>
                            <textarea rows="15" name="data" id="data" placeholder="Enter Frame Code" class="form-control" oninput="updatePreview()"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="logosection" class="form-label">Logo Code</label>
                            <textarea rows="10" name="logosection" id="logosection" placeholder="Enter Logo Code" class="form-control" oninput="updatePreview()"></textarea>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="status">Status</label></br>
                                <label class="custom-switch">
                                    <input type="checkbox" name="status" value="1" checked>
                                    <span class="switch-slider"></span>
                                </label>
                            </div>
                        
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="free_paid">Free / Paid</label></br>
                                <label class="custom-switch">
                                    <input type="checkbox" name="free_paid" value="1" checked>
                                    <span class="switch-slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">{{ __('Submit') }}</button>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Preview</label>
                            <div style="position: relative; border: 2px dashed #ddd; border-radius: 8px; padding: 20px; width: fit-content; min-height: 400px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; margin:auto;">
                                <img id="image-preview" alt="image preview" style="display: none; max-width: 100%; height: auto; max-height: 600px;">
                                <canvas id="preview-canvas" style="position: absolute; top: 20px; left: 20px; pointer-events: none;"></canvas>
                                <span id="preview-placeholder" style="color: #999; font-size: 14px;">Image preview will appear here</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
const defaultFrameCode = `[{
    "marginleft":"2", 
    "marginright":"20", 
    "marginbottom":"20",
    "margintop":"20",
    "size":"50",
    "type":[
        {
        "layouttype": "row",
        "data": [
        {
        "value": "Phone2", 
        "fontcolor": "#000000", 
        "textsize": "10",
        "iconcolor": "B",  
        "satus": "1", 
        "font": "arialbold", 
        "gravity": "TR", 
        "fonttype": "bold", 
        "marginleft": "1",
        "marginright": "5", 
        "marginbottom": "1", 
        "margintop": "4"
        },
        {
        "value": "Phone1", 
        "fontcolor": "#000000", 
        "textsize": "10", 
        "satus": "1",
        "iconcolor": "B", 
        "font": "arialbold", 
        "gravity": "TR", 
        "fonttype": "bold", 
        "marginleft": "10", 
        "marginright": "5", 
        "marginbottom": "15", 
        "margintop": "8"
        },
        {
        "value": "Business Name", 
        "fontcolor": "#000000", 
        "textsize": "8",
        "iconcolor": "B", 
        "satus": "1", 
        "font": "arialbold", 
        "gravity": "BC", 
        "fonttype": "bold",
        "marginleft": "1",  
        "marginright": "1", 
        "marginbottom": "12", 
        "margintop": "1"
        },
    {
        "value": "web", 
        "fontcolor": "#000000", 
        "textsize": "8",
        "iconcolor": "B", 
        "satus": "1",
        "font": "arialbold", 
        "gravity": "BL", 
        "fonttype": "bold",
        "marginleft": "50",  
        "marginright": "1", 
        "marginbottom": "8", 
        "margintop": "0"
        },
    {
        "value": "Email", 
        "fontcolor": "#000000", 
        "textsize": "8",
        "iconcolor": "B", 
        "satus": "1", 
        "font": "arialbold", 
        "gravity": "BR", 
        "fonttype": "bold", 
        "marginleft": "1",
        "marginright": "51", 
        "marginbottom": "8", 
        "margintop": "1"
        },
    {
        "value": "address", 
        "fontcolor": "#000000", 
        "textsize": "8",
        "iconcolor": "B", 
        "satus": "1", 
        "font": "arialbold", 
        "gravity": "BC", 
        "fonttype": "bold", 
        "marginleft": "5", 
        "marginright": "5", 
        "marginbottom": "4", 
        "margintop": "1"
        },
    {
        "value": "tagline", 
        "fontcolor": "#000000", 
        "textsize": "10",
        "iconcolor": "B", 
        "satus": "0", 
        "font": "arialbold", 
        "gravity": "BC", 
        "fonttype": "bold", 
        "digree": "-90", 
        "marginleft": "0", 
        "marginright": "1", 
        "marginbottom": "0", 
        "margintop": "1"
        },
    {
        "value": "Slogan", 
        "fontcolor": "#000000", 
        "textsize": "14",
        "iconcolor": "B", 
        "satus": "1", 
        "font": "arialbold", 
        "gravity": "BC", 
        "fonttype": "bold",
        "marginleft": "1", 
        "marginright": "1", 
        "marginbottom": "10", 
        "margintop": "1"
        }
    ]
    }]
    }
    ]
`;

const defaultLogoCode = `[{
    "marginleft":"2", 
    "marginright":"20", 
    "marginbottom":"20",
    "margintop":"20",
    "size":"50",
    "type":[
        {
        "layouttype": "row",
        "data": [
        {
        "value": "Logo", 
        "LogoFramsX": "555",
        "LogoFramsY": "8.8", 
        "LogoSlected": "1",
        "LogoSpecial": "1",
        "testsize": "12"
        },
        {
        "value": "NameStkers", 
        "LogoFramsX": "666",
        "LogoFramsY": "9.1", 
        "NameSelest": "0",
        "LogoSpecial": "10",
        "testsize": "11"
    }
    ]
    }]
    }
    ]
`;

function handleImageSelect(input) {
    const preview = document.getElementById('image-preview');
    const placeholder = document.getElementById('preview-placeholder');
    const label = input.nextElementSibling;
    const frameCodeField = document.getElementById('data');
    const logoCodeField = document.getElementById('logosection');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
            label.classList.add('has-file');
            
            // Auto-fill codes if empty
            if (!frameCodeField.value.trim()) {
                frameCodeField.value = defaultFrameCode;
            }
            if (!logoCodeField.value.trim()) {
                logoCodeField.value = defaultLogoCode;
            }
            
            setTimeout(updatePreview, 100);
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
        if (placeholder) placeholder.style.display = 'block';
        label.classList.remove('has-file');
    }
}

function updatePreview() {
    const img = document.getElementById('image-preview');
    const canvas = document.getElementById('preview-canvas');
    const frameCode = document.getElementById('data').value;
    
    if (!img.src || !canvas) return;
    if (img.naturalWidth === 0) {
        img.onload = updatePreview;
        return;
    }
    
    canvas.width = img.offsetWidth;
    canvas.height = img.offsetHeight;
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    try {
        if (frameCode) {
            const frameData = JSON.parse(frameCode);
            drawFrameElements(ctx, frameData, canvas.width, canvas.height);
        }
    } catch (e) {
        console.error('Invalid frame code:', e);
    }
}

function drawFrameElements(ctx, data, width, height) {
    data.forEach(section => {
        section.type?.forEach(layout => {
            layout.data?.forEach(element => {
                if (element.satus === "1") {
                    ctx.save();
                    
                    const fontSize = parseInt(element.textsize) * 2 || 24;
                    const fontWeight = element.fonttype === 'bold' ? 'bold' : 'normal';
                    ctx.font = `${fontWeight} ${fontSize}px Arial`;
                    ctx.fillStyle = element.fontcolor || '#000000';
                    
                    const ml = parseFloat(element.marginleft) || 0;
                    const mr = parseFloat(element.marginright) || 0;
                    const mt = parseFloat(element.margintop) || 0;
                    const mb = parseFloat(element.marginbottom) || 0;
                    const gravity = element.gravity || 'TL';
                    
                    let x, y;
                    
                    // X position
                    if (gravity.includes('L')) {
                        x = (width * ml / 100);
                        ctx.textAlign = 'left';
                    } else if (gravity.includes('R')) {
                        x = width - (width * mr / 100);
                        ctx.textAlign = 'right';
                    } else if (gravity.includes('C')) {
                        x = width / 2;
                        ctx.textAlign = 'center';
                    }
                    
                    // Y position
                    if (gravity.includes('T')) {
                        y = (height * mt / 100) + fontSize;
                    } else if (gravity.includes('B')) {
                        y = height - (height * mb / 100);
                    } else if (gravity.includes('C')) {
                        y = (height / 2) + (fontSize / 2);
                    }
                    
                    // Handle rotation if degree is specified
                    if (element.digree) {
                        const angle = parseFloat(element.digree) * Math.PI / 180;
                        ctx.translate(x, y);
                        ctx.rotate(angle);
                        ctx.fillText(element.value, 0, 0);
                    } else {
                        ctx.fillText(element.value, x, y);
                    }
                    
                    ctx.restore();
                }
            });
        });
    });
}
</script>
@endpush