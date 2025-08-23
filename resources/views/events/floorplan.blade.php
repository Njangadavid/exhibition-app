<x-event-layout :event="$event">

    <div class="py-4">
        <div class="container-fluid">
            <!-- Floorplan Creation Interface -->
            <div class="row">
                <!-- Floorplan Properties Panel -->
                <div class="col-lg-3" id="propertiesPanel">
                    <div class="card">
                        <div class="card-header py-2 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 small fw-bold">
                                <i class="bi bi-gear me-1 text-primary"></i>
                                Floorplan Properties
                            </h6>
                            <button class="btn btn-sm btn-outline-secondary py-1 px-2" id="hidePropertiesBtn">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                        </div>
                        <div class="card-body py-2">
                            <form class="d-flex flex-column gap-3">
                                <!-- Basic Settings -->
                                <div>
                                    <h6 class="fw-bold mb-2 small">Basic Settings</h6>
                                    
                                    <div class="mb-2">
                                        <label class="form-label small mb-1 fw-semibold">Floorplan Name</label>
                                        <input type="text" class="form-control form-control-sm" id="floorplanName" placeholder="Main Hall Floorplan">
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small mb-1 fw-semibold">Canvas Size</label>
                                        <select class="form-select form-select-sm" id="canvasSize">
                                            <option value="800x600">800 x 600 (Standard)</option>
                                            <option value="1024x768">1024 x 768 (HD)</option>
                                            <option value="1920x1080">1920 x 1080 (Full HD)</option>
                                            <option value="custom">Custom Size</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Color Scheme -->
                                <div>
                                    <h6 class="fw-bold mb-2 small">Color Scheme</h6>
                                    
                                    <div class="mb-2">
                                        <label class="form-label small mb-1 fw-semibold">Background Color</label>
                                        <div class="d-flex gap-2">
                                            <input type="color" value="#ffffff" class="form-control form-control-color" id="bgColor" style="width: 50px; height: 32px;">
                                            <input type="text" value="#ffffff" class="form-control form-control-sm" id="bgColorText" placeholder="#ffffff">
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small mb-1 fw-semibold">Default Fill Color</label>
                                        <div class="d-flex gap-2">
                                            <input type="color" value="#e5e7eb" class="form-control form-control-color" id="fillColor" style="width: 50px; height: 32px;">
                                            <input type="text" value="#e5e7eb" class="form-control form-control-sm" id="fillColorText" placeholder="#e5e7eb">
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small mb-1 fw-semibold">Border/Stroke Color</label>
                                        <div class="d-flex gap-2">
                                            <input type="color" value="#374151" class="form-control form-control-color" id="strokeColor" style="width: 50px; height: 32px;">
                                            <input type="text" value="#374151" class="form-control form-control-sm" id="strokeColorText" placeholder="#374151">
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small mb-1 fw-semibold">Text Color</label>
                                        <div class="d-flex gap-2">
                                            <input type="color" value="#111827" class="form-control form-control-color" id="textColor" style="width: 50px; height: 32px;">
                                            <input type="text" value="#111827" class="form-control form-control-sm" id="textColorText" placeholder="#111827">
                                        </div>
                                    </div>
                                </div>

                                <!-- Styling Options -->
                                <div>
                                    <h6 class="fw-bold mb-2 small">Styling Options</h6>
                                    
                                    <div class="mb-2">
                                        <label class="form-label small mb-1 fw-semibold">Border Width</label>
                                        <select class="form-select form-select-sm" id="borderWidth">
                                            <option value="1">1px (Thin)</option>
                                            <option value="2" selected>2px (Standard)</option>
                                            <option value="3">3px (Thick)</option>
                                            <option value="4">4px (Extra Thick)</option>
                                        </select>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small mb-1 fw-semibold">Font Family</label>
                                        <select class="form-select form-select-sm" id="fontFamily">
                                            <option value="Arial">Arial</option>
                                            <option value="Helvetica">Helvetica</option>
                                            <option value="Times New Roman">Times New Roman</option>
                                            <option value="Georgia">Georgia</option>
                                            <option value="Verdana">Verdana</option>
                                        </select>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small mb-1 fw-semibold">Font Size</label>
                                        <select class="form-select form-select-sm" id="fontSize">
                                            <option value="10">10px (Small)</option>
                                            <option value="12" selected>12px (Standard)</option>
                                            <option value="14">14px (Medium)</option>
                                            <option value="16">16px (Large)</option>
                                            <option value="18">18px (Extra Large)</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Grid Settings -->
                                <div>
                                    <h6 class="fw-bold mb-2 small">Grid Settings</h6>
                                    
                                    <div class="mb-2">
                                        <label class="form-label small mb-1 fw-semibold">Grid Size</label>
                                        <select class="form-select form-select-sm" id="gridSize">
                                            <option value="10">10px</option>
                                            <option value="20" selected>20px</option>
                                            <option value="25">25px</option>
                                            <option value="50">50px</option>
                                        </select>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small mb-1 fw-semibold">Grid Color</label>
                                        <div class="d-flex gap-2">
                                            <input type="color" value="#e5e7eb" class="form-control form-control-color" id="gridColor" style="width: 50px; height: 32px;">
                                            <input type="text" value="#e5e7eb" class="form-control form-control-sm" id="gridColorText" placeholder="#e5e7eb">
                                        </div>
                                    </div>

                                    <div class="form-check mb-1">
                                        <input type="checkbox" class="form-check-input" id="showGrid" checked>
                                        <label class="form-check-label small" for="showGrid">Show Grid</label>
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="snapToGrid" checked>
                                        <label class="form-check-label small" for="snapToGrid">Snap to Grid</label>
                                    </div>
                                </div>

                                <!-- Booking Properties -->
                                <div>
                                    <h6 class="fw-bold mb-2 small">Booking Properties</h6>
                                    
                                    <div class="mb-2">
                                        <label class="form-label small mb-1 fw-semibold">Default Booth Capacity</label>
                                        <input type="number" class="form-control form-control-sm" id="defaultBoothCapacity" value="5" min="1" max="20">
                                        <small class="form-text text-muted small">Maximum number of people per booth</small>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small mb-1 fw-semibold">Label Prefix</label>
                                        <input type="text" class="form-control form-control-sm" id="labelPrefix" value="A" maxlength="3">
                                        <small class="form-text text-muted small">Prefix for booth labels (A, B, C, etc.)</small>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small mb-1 fw-semibold">Starting Label Number</label>
                                        <input type="number" class="form-control form-control-sm" id="startingLabelNumber" value="1" min="1" max="99">
                                        <small class="form-text text-muted small">Starting number for booth labels</small>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small mb-1 fw-semibold">Default Label Position</label>
                                        <select class="form-select form-select-sm" id="defaultLabelPosition">
                                            <option value="top" selected>Top</option>
                                            <option value="bottom">Bottom</option>
                                            <option value="left">Left</option>
                                            <option value="right">Right</option>
                                            <option value="center">Center</option>
                                        </select>
                                        <small class="form-text text-muted small">Position of labels relative to items</small>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small mb-1 fw-semibold">Default Price</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text small">$</span>
                                            <input type="number" class="form-control form-control-sm" id="defaultPrice" value="100" min="0" step="0.01">
                                        </div>
                                        <small class="form-text text-muted small">Default price for bookable items</small>
                                    </div>

                                    <div class="form-check mb-1">
                                        <input type="checkbox" class="form-check-input" id="enableAutoLabeling" checked>
                                        <label class="form-check-label small" for="enableAutoLabeling">Enable Auto-Labeling</label>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="pt-2">
                                    <button type="button" class="btn btn-primary btn-sm w-100 mb-2 py-2" id="saveFloorplanBtn">
                                        <i class="bi bi-save me-1"></i>
                                        <span class="small">Save Floorplan</span>
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm w-100 py-2" id="resetDefaults">
                                        <i class="bi bi-arrow-clockwise me-1"></i>
                                        <span class="small">Reset to Defaults</span>
                                    </button>
                                    
                                    <!-- Floorplan Maintenance -->
                                    <hr class="my-3">
                                    <div class="small text-muted mb-2">Floorplan Maintenance</div>
                                    <button type="button" class="btn btn-outline-warning btn-sm w-100 mb-2 py-2" id="checkOrphanedBookingsBtn">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        <span class="small">Check Orphaned Bookings</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm w-100 py-2" id="cleanupOrphanedBookingsBtn" style="display: none;">
                                        <i class="bi bi-trash me-1"></i>
                                        <span class="small">Clean Up Orphaned Bookings</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Floorplan Canvas -->
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="h5 mb-0">
                                    <i class="bi bi-pencil-square me-2 text-success"></i>
                                    Floorplan Canvas
                                </h3>
                                
                                <!-- Item Properties Panel (shows when item is selected) -->
                                <div id="itemPropertiesPanel" class="card" style="display: none; position: fixed; right: 20px; top: 80px; z-index: 1000; min-width: 280px; max-width: 320px; max-height: calc(100vh - 100px); overflow-y: auto;">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0 small fw-bold">
                                            <i class="bi bi-gear me-1"></i>
                                            Item Properties
                                        </h6>
                                        <button type="button" class="btn-close btn-close-sm" id="closeItemProperties"></button>
                                    </div>
                                    <div class="card-body py-2">
                                        <div class="mb-2">
                                            <label class="form-label small mb-1 fw-semibold">Item Name</label>
                                            <input type="text" class="form-control form-control-sm" id="itemName" placeholder="Enter item name">
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label class="form-label small mb-1 fw-semibold">Custom Label</label>
                                            <input type="text" class="form-control form-control-sm" id="itemLabel" placeholder="Enter custom label">
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label class="form-label small mb-1 fw-semibold">Label Position</label>
                                            <select class="form-select form-select-sm" id="itemLabelPosition">
                                                <option value="">Use Default ({{ ucfirst($defaultLabelPosition ?? 'top') }})</option>
                                                <option value="top">Top</option>
                                                <option value="bottom">Bottom</option>
                                                <option value="left">Left</option>
                                                <option value="right">Right</option>
                                                <option value="center">Center</option>
                                            </select>
                                            <small class="form-text text-muted small">Override default label position</small>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="itemBookable">
                                                <label class="form-check-label small" for="itemBookable">Bookable</label>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-2" id="capacityGroup" style="display: none;">
                                            <label class="form-label small mb-1 fw-semibold">Max Capacity</label>
                                            <input type="number" class="form-control form-control-sm" id="itemMaxCapacity" min="1" max="20">
                                        </div>
                                        
                                        <div class="mb-2" id="priceGroup" style="display: none;">
                                            <label class="form-label small mb-1 fw-semibold">Price</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text small">$</span>
                                                <input type="text" class="form-control form-control-sm" id="itemPrice" min="0" step="0.01" placeholder="Custom price">
                                            </div>
                                            <small class="form-text text-muted small">Leave empty for default</small>
                                        </div>
                                        
                                    </div>
                                    <div class="card-footer py-2 d-flex gap-1 bg-light border-top">
                                        <button type="button" class="btn btn-primary btn-sm py-1 px-2" id="updateItemProperties">
                                            <i class="bi bi-check me-1"></i>
                                            <span class="small">Update</span>
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm py-1 px-2" id="resetItemProperties">
                                            <i class="bi bi-arrow-clockwise me-1"></i>
                                            <span class="small">Reset</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button class="btn btn-outline-primary btn-sm" id="showPropertiesBtn" style="display: none;">
                                        <i class="bi bi-gear me-1"></i>
                                        Show Properties
                                    </button>
                                    
                                    <!-- Shape Tools Dropdown -->
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-shapes me-1"></i>
                                            Shapes
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item shape-tool" href="#" data-shape="rectangle"><i class="bi bi-square me-2"></i>Rectangle</a></li>
                                            <li><a class="dropdown-item shape-tool" href="#" data-shape="circle"><i class="bi bi-circle me-2"></i>Circle</a></li>
                                            <li><a class="dropdown-item shape-tool" href="#" data-shape="triangle"><i class="bi bi-triangle me-2"></i>Triangle</a></li>
                                            <li><a class="dropdown-item shape-tool" href="#" data-shape="pentagon"><i class="bi bi-pentagon me-2"></i>Pentagon</a></li>
                                            <li><a class="dropdown-item shape-tool" href="#" data-shape="hexagon"><i class="bi bi-hexagon me-2"></i>Hexagon</a></li>
                                            <li><a class="dropdown-item shape-tool" href="#" data-shape="arrow"><i class="bi bi-arrow-right me-2"></i>Arrow</a></li>
                                            <li><a class="dropdown-item shape-tool" href="#" data-shape="line"><i class="bi bi-dash me-2"></i>Line</a></li>
                                        </ul>
                                    </div>

                                    <!-- Furniture Dropdown -->
                                    <div class="dropdown">
                                        <button class="btn btn-outline-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-house me-1"></i>
                                            Furniture
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item furniture-tool" href="#" data-furniture="table"><i class="bi bi-table me-2"></i>Table</a></li>
                                            <li><a class="dropdown-item furniture-tool" href="#" data-furniture="chair"><i class="bi bi-chair me-2"></i>Chair</a></li>
                                            <li><a class="dropdown-item furniture-tool" href="#" data-furniture="desk"><i class="bi bi-laptop me-2"></i>Desk</a></li>
                                            <li><a class="dropdown-item furniture-tool" href="#" data-furniture="booth"><i class="bi bi-shop me-2"></i>Booth</a></li>
                                            <li><a class="dropdown-item furniture-tool" href="#" data-furniture="counter"><i class="bi bi-collection me-2"></i>Counter</a></li>
                                        </ul>
                                    </div>

                                    <!-- Exhibition Items Dropdown -->
                                    <div class="dropdown">
                                        <button class="btn btn-outline-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-display me-1"></i>
                                            Exhibition
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item exhibition-tool" href="#" data-item="stage"><i class="bi bi-mic-fill me-2"></i>Stage</a></li>
                                            <li><a class="dropdown-item exhibition-tool" href="#" data-item="screen"><i class="bi bi-tv me-2"></i>Screen</a></li>
                                            <li><a class="dropdown-item exhibition-tool" href="#" data-item="projector"><i class="bi bi-projector me-2"></i>Projector</a></li>
                                            <li><a class="dropdown-item exhibition-tool" href="#" data-item="banner"><i class="bi bi-flag me-2"></i>Banner</a></li>
                                            <li><a class="dropdown-item exhibition-tool" href="#" data-item="kiosk"><i class="bi bi-phone me-2"></i>Kiosk</a></li>
                                        </ul>
                                    </div>

                                    <!-- People & Access Dropdown -->
                                    <div class="dropdown">
                                        <button class="btn btn-outline-warning btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-people me-1"></i>
                                            People & Access
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item people-tool" href="#" data-item="person"><i class="bi bi-person-circle me-2"></i>Person</a></li>
                                            <li><a class="dropdown-item people-tool" href="#" data-item="group"><i class="bi bi-people me-2"></i>Group</a></li>
                                            <li><a class="dropdown-item people-tool" href="#" data-item="entrance"><i class="bi bi-door-open me-2"></i>Entrance</a></li>
                                            <li><a class="dropdown-item people-tool" href="#" data-item="exit"><i class="bi bi-box-arrow-right me-2"></i>Exit</a></li>
                                            <li><a class="dropdown-item people-tool" href="#" data-item="elevator"><i class="bi bi-elevator me-2"></i>Elevator</a></li>
                                            <li><a class="dropdown-item people-tool" href="#" data-item="stairs"><i class="bi bi-stairs me-2"></i>Stairs</a></li>
                                        </ul>
                                    </div>

                                    <!-- Utilities Dropdown -->
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-tools me-1"></i>
                                            Utilities
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item utility-tool" href="#" data-item="restroom"><i class="bi bi-person-wheelchair me-2"></i>Restroom</a></li>
                                            <li><a class="dropdown-item utility-tool" href="#" data-item="security"><i class="bi bi-shield-check me-2"></i>Security</a></li>
                                            <li><a class="dropdown-item utility-tool" href="#" data-item="firstaid"><i class="bi bi-heart-pulse me-2"></i>First Aid</a></li>
                                            <li><a class="dropdown-item utility-tool" href="#" data-item="fire"><i class="bi bi-fire me-2"></i>Fire Safety</a></li>
                                                                    <li><a class="dropdown-item utility-tool" href="#" data-item="power"><i class="bi bi-lightning me-2"></i>Power Outlet</a></li>
                    </ul>
                </div>
                
                <!-- Outdoor Elements Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-tree me-2"></i>Outdoor
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item outdoor-tool" href="#" data-item="tree"><i class="bi bi-tree me-2"></i>Tree</a></li>
                        <li><a class="dropdown-item outdoor-tool" href="#" data-item="road"><i class="bi bi-arrow-left-right me-2"></i>Road</a></li>
                        <li><a class="dropdown-item outdoor-tool" href="#" data-item="building"><i class="bi bi-building me-2"></i>Building</a></li>
                        <li><a class="dropdown-item outdoor-tool" href="#" data-item="parking"><i class="bi bi-p-square me-2"></i>Parking</a></li>
                        <li><a class="dropdown-item outdoor-tool" href="#" data-item="fountain"><i class="bi bi-droplet me-2"></i>Fountain</a></li>
                        <li><a class="dropdown-item outdoor-tool" href="#" data-item="garden"><i class="bi bi-flower1 me-2"></i>Garden</a></li>
                    </ul>
                </div>

                                    <button class="btn btn-outline-primary btn-sm" id="addTextBtn">
                                        <i class="bi bi-type me-1"></i>
                                        Text
                                    </button>
                                    
                                    <button class="btn btn-outline-danger btn-sm" id="clearCanvasBtn">
                                        <i class="bi bi-trash me-1"></i>
                                        Clear
                                    </button>
                                    
                                    <button type="button" class="btn btn-success btn-sm" id="saveFloorplanBtnTop">
                                        <i class="bi bi-save me-1"></i>
                                        Save
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Canvas Area -->
                            <div class="border border-2 border-dashed border-secondary rounded bg-light d-flex align-items-center justify-content-center position-relative" style="min-height: 500px;">
                                <canvas id="floorplanCanvas" width="800" height="600" style="border: 1px solid #ccc; cursor: crosshair;"></canvas>
                                
                                <!-- Canvas Instructions (shown when empty) -->
                                <div id="canvasInstructions" class="position-absolute text-center">
                                    <i class="bi bi-mouse text-muted" style="font-size: 3rem;"></i>
                                    <h4 class="h6 mt-3 mb-2">Interactive Floorplan Canvas</h4>
                                    <p class="text-muted mb-3">Click and drag to create areas, add text, or upload images</p>
                                    <div class="text-muted small">
                                        <p class="mb-1">• Click "Add Area" to create zones</p>
                                        <p class="mb-1">• Use "Add Text" for labels</p>
                                        <p class="mb-0">• Upload images for logos or icons</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <style>
        /* Properties panel toggle styles */
        .properties-collapsed .col-lg-4 {
            display: none !important;
        }
        
        .properties-collapsed .col-lg-8 {
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }
        
        /* Smooth transitions */
        .col-lg-4, .col-lg-8 {
            transition: all 0.3s ease;
        }
    </style>

    <script>
        // Floorplan Canvas JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('floorplanCanvas');
            const ctx = canvas.getContext('2d');
            const propertiesPanel = document.getElementById('propertiesPanel');
            const hidePropertiesBtn = document.getElementById('hidePropertiesBtn');
            const showPropertiesBtn = document.getElementById('showPropertiesBtn');
            const canvasInstructions = document.getElementById('canvasInstructions');
            
            let isDrawing = false;
            let startX, startY;
            let selectedShape = null;
            let shapes = [];
            let currentTool = 'select';
            let dragOffsetX = 0;
            let dragOffsetY = 0;
            
            // Resize functionality variables
            let isResizing = false;
            let resizeHandle = null;
            const handleSize = 12; // Increased from 8 to 12 for easier clicking
            const handleDetectionSize = 16; // Larger detection area than visual size
            
            // Rotation functionality variables
            let isRotating = false;
            let rotationCenter = null;
            
            // Booking system variables
            let defaultBoothCapacity = 5;
            let labelPrefix = 'A';
            let startingLabelNumber = 1;
            let enableAutoLabeling = true;
            let boothCounter = 0; // Track booth count for auto-labeling
            let defaultLabelPosition = 'top';
            let defaultPrice = 100;
            
            // Change tracking for save button
            let hasUnsavedChanges = false;
            let originalFloorplanState = null;
            
            // Initialize canvas
            function initCanvas() {
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                drawGrid();
            }
            
            // Toggle canvas instructions visibility
            function toggleCanvasInstructions() {
                if (shapes.length > 0) {
                    canvasInstructions.style.display = 'none';
                } else {
                    canvasInstructions.style.display = 'block';
                }
            }
            
            // Track changes and update save button visibility
            function trackChanges() {
                hasUnsavedChanges = true;
                updateSaveButtonVisibility();
            }
            
            // Update save button visibility based on changes
            function updateSaveButtonVisibility() {
                const saveBtn = document.getElementById('saveFloorplanBtnTop');
                if (hasUnsavedChanges) {
                    saveBtn.style.display = 'inline-block';
                    saveBtn.classList.remove('btn-outline-success');
                    saveBtn.classList.add('btn-success');
                } else {
                    saveBtn.style.display = 'none';
                }
            }
            
            // Save current state for change detection
            function saveCurrentState() {
                originalFloorplanState = JSON.stringify({
                    shapes: shapes,
                    properties: {
                        bgColor: document.getElementById('bgColor').value,
                        fillColor: document.getElementById('fillColor').value,
                        strokeColor: document.getElementById('strokeColor').value,
                        textColor: document.getElementById('textColor').value,
                        borderWidth: document.getElementById('borderWidth').value,
                        fontFamily: document.getElementById('fontFamily').value,
                        fontSize: document.getElementById('fontSize').value,
                        gridSize: document.getElementById('gridSize').value,
                        gridColor: document.getElementById('gridColor').value,
                        showGrid: document.getElementById('showGrid').checked,
                        snapToGrid: document.getElementById('snapToGrid').checked,
                        defaultBoothCapacity: document.getElementById('defaultBoothCapacity').value,
                        labelPrefix: document.getElementById('labelPrefix').value,
                        startingLabelNumber: document.getElementById('startingLabelNumber').value,
                        defaultLabelPosition: document.getElementById('defaultLabelPosition').value,
                        defaultPrice: document.getElementById('defaultPrice').value,
                        enableAutoLabeling: document.getElementById('enableAutoLabeling').checked
                    }
                });
                hasUnsavedChanges = false;
                updateSaveButtonVisibility();
            }
            
            // Resize canvas function
            function resizeCanvas(width, height) {
                // Store current canvas content
                const currentShapes = [...shapes];
                const currentSelectedShape = selectedShape;
                
                // Resize canvas
                canvas.width = width;
                canvas.height = height;
                
                // Reinitialize with new size
                initCanvas();
                
                // Restore shapes and redraw
                shapes = currentShapes;
                selectedShape = currentSelectedShape;
                redrawCanvas();
                
                // Update canvas size display
                document.getElementById('canvasSize').value = `${width}x${height}`;
                
                // Track changes when canvas is resized
                trackChanges();
            }
            
            // Draw grid
            function drawGrid() {
                const gridSize = parseInt(document.getElementById('gridSize').value);
                const showGrid = document.getElementById('showGrid').checked;
                
                if (!showGrid) return;
                
                ctx.strokeStyle = document.getElementById('gridColor').value;
                ctx.lineWidth = 0.5;
                
                for (let x = 0; x <= canvas.width; x += gridSize) {
                    ctx.beginPath();
                    ctx.moveTo(x, 0);
                    ctx.lineTo(x, canvas.height);
                    ctx.stroke();
                }
                
                for (let y = 0; y <= canvas.height; y += gridSize) {
                    ctx.beginPath();
                    ctx.moveTo(0, y);
                    ctx.lineTo(canvas.width, y);
                    ctx.stroke();
                }
            }
            
            // Draw resize handles for selected shape
            function drawResizeHandles(shape) {
                // Handle different shape types
                let width, height;
                
                if (shape.type === 'circle') {
                    // Circle uses radius
                    width = (shape.radius || 30) * 2;
                    height = (shape.radius || 30) * 2;
                } else if (shape.type === 'triangle' || shape.type === 'pentagon' || shape.type === 'hexagon') {
                    // Polygon shapes use size
                    width = shape.size || 40;
                    height = shape.size || 40;
                } else if (shape.width || shape.height) {
                    // Regular shapes with width/height
                    width = shape.width || 40;
                    height = shape.height || 40;
                } else {
                    // Fallback for other shapes
                    width = shape.size || 40;
                    height = shape.size || 40;
                }
                
                ctx.fillStyle = '#007bff';
                ctx.strokeStyle = '#ffffff';
                ctx.lineWidth = 2;
                
                // Draw selection outline
                ctx.strokeStyle = '#007bff';
                ctx.lineWidth = 2;
                ctx.setLineDash([5, 5]);
                ctx.strokeRect(shape.x - 1, shape.y - 1, width + 2, height + 2);
                ctx.setLineDash([]);
                
                // Draw resize handles (squares)
                const handles = [
                    { x: shape.x - handleSize/2, y: shape.y - handleSize/2, cursor: 'nw-resize', type: 'nw' },
                    { x: shape.x + width/2 - handleSize/2, y: shape.y - handleSize/2, cursor: 'n-resize', type: 'n' },
                    { x: shape.x + width - handleSize/2, y: shape.y - handleSize/2, cursor: 'ne-resize', type: 'ne' },
                    { x: shape.x + width - handleSize/2, y: shape.y + height/2 - handleSize/2, cursor: 'e-resize', type: 'e' },
                    { x: shape.x + width - handleSize/2, y: shape.y + height - handleSize/2, cursor: 'se-resize', type: 'se' },
                    { x: shape.x + width/2 - handleSize/2, y: shape.y + height - handleSize/2, cursor: 's-resize', type: 's' },
                    { x: shape.x - handleSize/2, y: shape.y + height - handleSize/2, cursor: 'sw-resize', type: 'sw' },
                    { x: shape.x - handleSize/2, y: shape.y + height/2 - handleSize/2, cursor: 'w-resize', type: 'w' }
                ];
                
                handles.forEach(handle => {
                    ctx.fillStyle = '#007bff';
                    ctx.fillRect(handle.x, handle.y, handleSize, handleSize);
                    ctx.strokeStyle = '#ffffff';
                    ctx.lineWidth = 1;
                    ctx.strokeRect(handle.x, handle.y, handleSize, handleSize);
                });
                
                // Draw rotation handle (circle above the shape)
                const rotationHandleY = shape.y - handleSize * 2;
                const rotationHandleX = shape.x + width/2 - handleSize/2;
                
                ctx.fillStyle = '#FF6B35'; // Orange color for rotation handle
                ctx.beginPath();
                ctx.arc(rotationHandleX + handleSize/2, rotationHandleY + handleSize/2, handleSize/2, 0, 2 * Math.PI);
                ctx.fill();
                ctx.strokeStyle = '#FFF';
                ctx.lineWidth = 2;
                ctx.stroke();
                
                // Draw rotation icon (curved arrow)
                ctx.strokeStyle = '#FFF';
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.arc(rotationHandleX + handleSize/2, rotationHandleY + handleSize/2, handleSize/3, -Math.PI/4, Math.PI/2);
                ctx.stroke();
                // Arrow head
                ctx.beginPath();
                ctx.moveTo(rotationHandleX + handleSize/2 + handleSize/3 * Math.cos(Math.PI/2), 
                           rotationHandleY + handleSize/2 + handleSize/3 * Math.sin(Math.PI/2));
                ctx.lineTo(rotationHandleX + handleSize/2 + handleSize/3 * Math.cos(Math.PI/2 - Math.PI/6), 
                           rotationHandleY + handleSize/2 + handleSize/3 * Math.sin(Math.PI/2 - Math.PI/6));
                ctx.lineTo(rotationHandleX + handleSize/2 + handleSize/3 * Math.cos(Math.PI/2 + Math.PI/6), 
                           rotationHandleY + handleSize/2 + handleSize/3 * Math.sin(Math.PI/2 + Math.PI/6));
                ctx.stroke();
            }
            
            // Check if rotation handle is clicked
            function isRotationHandleClicked(x, y, shape) {
                if (!shape) return false;
                
                // Handle different shape types
                let width, height;
                
                if (shape.type === 'circle') {
                    width = (shape.radius || 30) * 2;
                    height = (shape.radius || 30) * 2;
                } else if (shape.type === 'triangle' || shape.type === 'pentagon' || shape.type === 'hexagon') {
                    width = shape.size || 40;
                    height = shape.size || 40;
                } else if (shape.width || shape.height) {
                    width = shape.width || 40;
                    height = shape.height || 40;
                } else {
                    width = shape.size || 40;
                    height = shape.size || 40;
                }
                
                const rotationHandleY = shape.y - handleSize * 2;
                const rotationHandleX = shape.x + width/2 - handleSize/2;
                
                return x >= rotationHandleX && x <= rotationHandleX + handleSize &&
                       y >= rotationHandleY && y <= rotationHandleY + handleSize;
            }
            
            // Get resize handle at position
            function getResizeHandle(x, y, shape) {
                if (!shape) return null;
                
                // Handle different shape types
                let width, height;
                
                if (shape.type === 'circle') {
                    // Circle uses radius
                    width = (shape.radius || 30) * 2;
                    height = (shape.radius || 30) * 2;
                } else if (shape.type === 'triangle' || shape.type === 'pentagon' || shape.type === 'hexagon') {
                    // Polygon shapes use size
                    width = shape.size || 40;
                    height = shape.size || 40;
                } else if (shape.width || shape.height) {
                    // Regular shapes with width/height
                    width = shape.width || 40;
                    height = shape.height || 40;
                } else {
                    // Fallback for other shapes
                    width = shape.size || 40;
                    height = shape.size || 40;
                }
                
                const handles = [
                    { x: shape.x - handleDetectionSize/2, y: shape.y - handleDetectionSize/2, type: 'nw' },
                    { x: shape.x + width/2 - handleDetectionSize/2, y: shape.y - handleDetectionSize/2, type: 'n' },
                    { x: shape.x + width - handleDetectionSize/2, y: shape.y - handleDetectionSize/2, type: 'ne' },
                    { x: shape.x + width - handleDetectionSize/2, y: shape.y + height/2 - handleDetectionSize/2, type: 'e' },
                    { x: shape.x + width - handleDetectionSize/2, y: shape.y + height - handleDetectionSize/2, type: 'se' },
                    { x: shape.x + width/2 - handleDetectionSize/2, y: shape.y + height - handleDetectionSize/2, type: 's' },
                    { x: shape.x - handleDetectionSize/2, y: shape.y + height - handleDetectionSize/2, type: 'sw' },
                    { x: shape.x - handleDetectionSize/2, y: shape.y + height/2 - handleDetectionSize/2, type: 'w' }
                ];
                
                for (const handle of handles) {
                    if (x >= handle.x && x <= handle.x + handleDetectionSize &&
                        y >= handle.y && y <= handle.y + handleDetectionSize) {
                        return handle.type;
                    }
                }
                return null;
            }
            
            // Check if point is inside a shape (excluding resize handles)
            function isPointInShape(x, y, shape) {
                if (!shape) return false;
                
                // Handle different shape types
                let width, height;
                
                if (shape.type === 'circle') {
                    // Circle uses radius
                    width = (shape.radius || 30) * 2;
                    height = (shape.radius || 30) * 2;
                } else if (shape.type === 'triangle' || shape.type === 'pentagon' || shape.type === 'hexagon') {
                    // Polygon shapes use size
                    width = shape.size || 40;
                    height = shape.size || 40;
                } else if (shape.type === 'arrow' || shape.type === 'line') {
                    // Arrow and line use width/height with special handling
                    width = shape.width || 80;
                    height = shape.type === 'line' ? Math.max(shape.height || 2, 8) : shape.height || 20; // Make line easier to select
                } else if (shape.width || shape.height) {
                    // Regular shapes with width/height
                    width = shape.width || 40;
                    height = shape.height || 40;
                } else {
                    // Fallback for other shapes
                    width = shape.size || 40;
                    height = shape.size || 40;
                }
                
                // Check if point is within shape bounds
                return x >= shape.x && x <= shape.x + width &&
                       y >= shape.y && y <= shape.y + height;
            }
            
            // Redraw all shapes
            function redrawCanvas() {
                ctx.fillStyle = document.getElementById('bgColor').value;
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                drawGrid();
                
                shapes.forEach(shape => {
                    drawShape(shape);
                    // Draw labels for bookable items
                    drawItemLabel(shape);
                });
                
                // Draw resize handles for selected shape (always show if selected)
                if (selectedShape) {
                    drawResizeHandles(selectedShape);
                }
                
                // Toggle canvas instructions visibility
                toggleCanvasInstructions();
            }
            
            // Draw individual shape
            function drawShape(shape) {
                ctx.strokeStyle = shape.strokeColor || '#374151';
                ctx.fillStyle = shape.fillColor || '#e5e7eb';
                ctx.lineWidth = shape.borderWidth || 2;
                ctx.font = `${shape.fontSize || 12}px ${shape.fontFamily || 'Arial'}`;
                
                // Calculate center for rotation
                let centerX, centerY;
                if (shape.type === 'circle') {
                    centerX = shape.x + shape.radius;
                    centerY = shape.y + shape.radius;
                } else if (shape.type === 'triangle' || shape.type === 'pentagon' || shape.type === 'hexagon') {
                    centerX = shape.x + shape.size / 2;
                    centerY = shape.y + shape.size / 2;
                } else {
                    centerX = shape.x + (shape.width || 40) / 2;
                    centerY = shape.y + (shape.height || 40) / 2;
                }
                
                // Rotation will be handled in individual drawing functions
                
                switch(shape.type) {
                    case 'rectangle':
                        if (shape.rotation && shape.rotation !== 0) {
                            ctx.save();
                            ctx.translate(centerX, centerY);
                            ctx.rotate(shape.rotation * Math.PI / 180);
                            ctx.translate(-centerX, -centerY);
                        }
                        ctx.fillRect(shape.x, shape.y, shape.width, shape.height);
                        ctx.strokeRect(shape.x, shape.y, shape.width, shape.height);
                        if (shape.rotation && shape.rotation !== 0) {
                            ctx.restore();
                        }
                        break;
                        
                    case 'circle':
                        if (shape.rotation && shape.rotation !== 0) {
                            ctx.save();
                            ctx.translate(centerX, centerY);
                            ctx.rotate(shape.rotation * Math.PI / 180);
                            ctx.translate(-centerX, -centerY);
                        }
                        ctx.beginPath();
                        ctx.arc(centerX, centerY, shape.radius, 0, 2 * Math.PI);
                        ctx.fill();
                        ctx.stroke();
                        if (shape.rotation && shape.rotation !== 0) {
                            ctx.restore();
                        }
                        break;
                        
                    case 'triangle':
                        const size = shape.size || 50;
                        ctx.beginPath();
                        ctx.moveTo(shape.x + size/2, shape.y); // Top point
                        ctx.lineTo(shape.x, shape.y + size); // Bottom left
                        ctx.lineTo(shape.x + size, shape.y + size); // Bottom right
                        ctx.closePath();
                        ctx.fill();
                        ctx.stroke();
                        break;
                        
                    case 'pentagon':
                    case 'hexagon':
                        drawPolygon(shape);
                        break;
                        
                    case 'arrow':
                        drawArrow(shape);
                        return;
                        
                    case 'line':
                        drawLine(shape);
                        return;
                        
                    case 'text':
                        const textCenterX = shape.x + (shape.width || 120) / 2;
                        const textCenterY = shape.y + (shape.height || 30) / 2;
                        
                        // Apply rotation if shape has rotation
                        if (shape.rotation && shape.rotation !== 0) {
                            ctx.save();
                            ctx.translate(textCenterX, textCenterY);
                            ctx.rotate(shape.rotation * Math.PI / 180);
                            ctx.translate(-textCenterX, -textCenterY);
                        }
                        
                        // Set text properties
                        ctx.fillStyle = shape.textColor || '#111827';
                        ctx.font = `${shape.fontSize || 16}px ${shape.fontFamily || 'Arial'}`;
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        
                        // Draw text at center of the shape
                        ctx.fillText(shape.text, textCenterX, textCenterY);
                        
                        // Draw text boundary rectangle for visual feedback
                        ctx.strokeStyle = selectedShape === shape ? '#007bff' : 'rgba(0,0,0,0.1)';
                        ctx.lineWidth = 1;
                        ctx.strokeRect(shape.x, shape.y, shape.width || 120, shape.height || 30);
                        
                        // Restore canvas context if rotation was applied
                        if (shape.rotation && shape.rotation !== 0) {
                            ctx.restore();
                        }
                        break;
                        
                    // Furniture items
                    case 'table':
                        drawTable(shape);
                        return; // Return early to avoid default rectangle drawing
                    case 'chair':
                        drawChair(shape);
                        return;
                    case 'desk':
                        drawDesk(shape);
                        return;
                    case 'booth':
                        drawBooth(shape);
                        return;
                    case 'counter':
                        drawCounter(shape);
                        return;
                        
                    // Exhibition items
                    case 'stage':
                        drawStage(shape);
                        return;
                    case 'screen':
                        drawScreen(shape);
                        return;
                    case 'projector':
                        drawProjector(shape);
                        return;
                    case 'banner':
                        drawBanner(shape);
                        return;
                    case 'kiosk':
                        drawKiosk(shape);
                        return;
                        
                    // People and access
                    case 'person':
                        drawPerson(shape);
                        return;
                    case 'group':
                        drawGroup(shape);
                        return;
                    case 'entrance':
                        drawEntrance(shape);
                        return;
                    case 'exit':
                        drawExit(shape);
                        return;
                    case 'elevator':
                        drawElevator(shape);
                        return;
                    case 'stairs':
                        drawStairs(shape);
                        return;
                        
                    // Utilities
                    case 'restroom':
                        drawRestroom(shape);
                        return;
                    case 'security':
                        drawSecurity(shape);
                        return;
                    case 'firstaid':
                        drawFirstAid(shape);
                        return;
                    case 'fire':
                        drawFireSafety(shape);
                        return;
                    case 'power':
                        drawPowerOutlet(shape);
                        return;
                        
                    // Outdoor elements
                    case 'tree':
                        drawTree(shape);
                        return;
                    case 'road':
                        drawRoad(shape);
                        return;
                    case 'building':
                        drawBuilding(shape);
                        return;
                    case 'parking':
                        drawParking(shape);
                        return;
                    case 'fountain':
                        drawFountain(shape);
                        return;
                    case 'garden':
                        drawGarden(shape);
                        return;
                }
                
            }
            
            // Drawing functions for complex shapes
            function drawPolygon(shape) {
                const sides = shape.type === 'pentagon' ? 5 : 6;
                const size = shape.size || 40;
                const centerX = shape.x + size/2;
                const centerY = shape.y + size/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                ctx.beginPath();
                for (let i = 0; i < sides; i++) {
                    const angle = (i * 2 * Math.PI) / sides - Math.PI / 2;
                    const x = centerX + (size/2) * Math.cos(angle);
                    const y = centerY + (size/2) * Math.sin(angle);
                    if (i === 0) ctx.moveTo(x, y);
                    else ctx.lineTo(x, y);
                }
                ctx.closePath();
                ctx.fill();
                ctx.stroke();
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            // Draw arrow shape
            function drawArrow(shape) {
                const width = shape.width || 80;
                const height = shape.height || 20;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Arrow shaft
                ctx.beginPath();
                ctx.moveTo(shape.x, centerY);
                ctx.lineTo(shape.x + width - height, centerY);
                ctx.lineWidth = shape.borderWidth || 3;
                ctx.stroke();
                
                // Arrow head
                ctx.beginPath();
                ctx.moveTo(shape.x + width - height, centerY);
                ctx.lineTo(shape.x + width - height - height/2, shape.y);
                ctx.lineTo(shape.x + width - height - height/2, shape.y + height);
                ctx.closePath();
                ctx.fill();
                ctx.stroke();
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            // Draw line shape
            function drawLine(shape) {
                const width = shape.width || 80;
                const height = shape.height || 2;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Line
                ctx.beginPath();
                ctx.moveTo(shape.x, centerY);
                ctx.lineTo(shape.x + width, centerY);
                ctx.lineWidth = height;
                ctx.stroke();
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawTable(shape) {
                const width = shape.width || 80;
                const height = shape.height || 40;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Table top
                ctx.fillStyle = '#8B4513';
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#654321';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Table legs (4 corners)
                const legSize = 4;
                const legHeight = 8;
                ctx.fillStyle = '#654321';
                
                // Top-left leg
                ctx.fillRect(shape.x + 3, shape.y + 3, legSize, legSize);
                ctx.fillRect(shape.x + 3, shape.y + height + 2, legSize, legHeight);
                
                // Top-right leg
                ctx.fillRect(shape.x + width - 7, shape.y + 3, legSize, legSize);
                ctx.fillRect(shape.x + width - 7, shape.y + height + 2, legSize, legHeight);
                
                // Bottom-left leg
                ctx.fillRect(shape.x + 3, shape.y + height - 7, legSize, legSize);
                ctx.fillRect(shape.x + 3, shape.y + height + 2, legSize, legHeight);
                
                // Bottom-right leg
                ctx.fillRect(shape.x + width - 7, shape.y + height - 7, legSize, legSize);
                ctx.fillRect(shape.x + width - 7, shape.y + height + 2, legSize, legHeight);
                
                // Table edge highlight (to give 3D effect)
                ctx.fillStyle = '#A0522D';
                ctx.fillRect(shape.x, shape.y, width, 3);
                ctx.fillRect(shape.x, shape.y, 3, height);
                
                // Wood grain effect (optional decorative lines)
                ctx.strokeStyle = '#654321';
                ctx.lineWidth = 0.5;
                for (let i = 0; i < 3; i++) {
                    const y = shape.y + height/4 + i * (height/4);
                    ctx.beginPath();
                    ctx.moveTo(shape.x + 10, y);
                    ctx.lineTo(shape.x + width - 10, y);
                    ctx.stroke();
                }
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawChair(shape) {
                const width = shape.width || 25;
                const height = shape.height || 25;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Backrest (upper part)
                ctx.fillStyle = '#654321';
                ctx.fillRect(shape.x, shape.y, width, height * 0.4);
                ctx.strokeStyle = '#4A4A4A';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x, shape.y, width, height * 0.4);
                
                // Seat (lower part)
                ctx.fillStyle = '#8B4513';
                ctx.fillRect(shape.x, shape.y + height * 0.4, width, height * 0.6);
                ctx.strokeRect(shape.x, shape.y + height * 0.4, width, height * 0.6);
                
                // Chair legs (4 legs)
                ctx.fillStyle = '#654321';
                const legWidth = 2;
                const legExtension = 3;
                
                // Front legs
                ctx.fillRect(shape.x + 2, shape.y + height, legWidth, legExtension);
                ctx.fillRect(shape.x + width - 4, shape.y + height, legWidth, legExtension);
                
                // Back legs
                ctx.fillRect(shape.x + 2, shape.y + height * 0.4, legWidth, legExtension + height * 0.6);
                ctx.fillRect(shape.x + width - 4, shape.y + height * 0.4, legWidth, legExtension + height * 0.6);
                
                // Chair back vertical slats for detail
                ctx.strokeStyle = '#4A4A4A';
                ctx.lineWidth = 1;
                for (let i = 1; i < 4; i++) {
                    const x = shape.x + (width / 4) * i;
                    ctx.beginPath();
                    ctx.moveTo(x, shape.y + 2);
                    ctx.lineTo(x, shape.y + height * 0.4 - 2);
                    ctx.stroke();
                }
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawDesk(shape) {
                const width = shape.width || 100;
                const height = shape.height || 60;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Main desk surface
                ctx.fillStyle = '#8B4513';
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#654321';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Desk edge highlight (3D effect)
                ctx.fillStyle = '#A0522D';
                ctx.fillRect(shape.x, shape.y, width, 3);
                ctx.fillRect(shape.x, shape.y, 3, height);
                
                // Monitor/screen area
                ctx.fillStyle = '#000';
                const monitorWidth = width * 0.3;
                const monitorHeight = height * 0.35;
                const monitorX = shape.x + width * 0.1;
                const monitorY = shape.y + height * 0.1;
                ctx.fillRect(monitorX, monitorY, monitorWidth, monitorHeight);
                
                // Monitor screen (blue glow)
                ctx.fillStyle = '#1E90FF';
                ctx.fillRect(monitorX + 2, monitorY + 2, monitorWidth - 4, monitorHeight - 4);
                
                // Monitor stand
                ctx.fillStyle = '#696969';
                const standWidth = monitorWidth * 0.3;
                const standHeight = height * 0.15;
                ctx.fillRect(monitorX + (monitorWidth - standWidth)/2, monitorY + monitorHeight, standWidth, standHeight);
                
                // Keyboard area
                ctx.fillStyle = '#F5F5F5';
                const keyboardWidth = width * 0.4;
                const keyboardHeight = height * 0.2;
                const keyboardX = shape.x + width * 0.5;
                const keyboardY = shape.y + height * 0.6;
                ctx.fillRect(keyboardX, keyboardY, keyboardWidth, keyboardHeight);
                ctx.strokeStyle = '#D3D3D3';
                ctx.lineWidth = 1;
                ctx.strokeRect(keyboardX, keyboardY, keyboardWidth, keyboardHeight);
                
                // Keyboard keys (small rectangles)
                ctx.fillStyle = '#E0E0E0';
                for (let row = 0; row < 3; row++) {
                    for (let col = 0; col < 8; col++) {
                        const keyX = keyboardX + 5 + col * (keyboardWidth - 10) / 8;
                        const keyY = keyboardY + 3 + row * (keyboardHeight - 6) / 3;
                        const keyW = (keyboardWidth - 15) / 8;
                        const keyH = (keyboardHeight - 9) / 3;
                        ctx.fillRect(keyX, keyY, keyW, keyH);
                    }
                }
                
                // Mouse area
                ctx.fillStyle = '#808080';
                const mouseX = keyboardX + keyboardWidth + 5;
                const mouseY = keyboardY + keyboardHeight * 0.3;
                ctx.beginPath();
                ctx.ellipse(mouseX, mouseY, 8, 12, 0, 0, 2 * Math.PI);
                ctx.fill();
                
                // Desk drawers/storage
                ctx.fillStyle = '#654321';
                ctx.strokeStyle = '#4A4A4A';
                ctx.lineWidth = 1;
                
                // Right side drawers
                const drawerWidth = width * 0.25;
                const drawerHeight = height * 0.15;
                for (let i = 0; i < 2; i++) {
                    const drawerX = shape.x + width - drawerWidth - 5;
                    const drawerY = shape.y + height * 0.2 + i * (drawerHeight + 5);
                    ctx.fillRect(drawerX, drawerY, drawerWidth, drawerHeight);
                    ctx.strokeRect(drawerX, drawerY, drawerWidth, drawerHeight);
                    
                    // Drawer handle
                    ctx.fillStyle = '#C0C0C0';
                    ctx.fillRect(drawerX + drawerWidth - 8, drawerY + drawerHeight/2 - 2, 4, 4);
                    ctx.fillStyle = '#654321';
                }
                
                // Desk legs
                ctx.fillStyle = '#654321';
                const legWidth = 6;
                const legHeight = 10;
                
                // 4 legs
                ctx.fillRect(shape.x + 5, shape.y + height + 2, legWidth, legHeight);
                ctx.fillRect(shape.x + width - 11, shape.y + height + 2, legWidth, legHeight);
                ctx.fillRect(shape.x + 5, shape.y + height + 2, legWidth, legHeight);
                ctx.fillRect(shape.x + width - 11, shape.y + height + 2, legWidth, legHeight);
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawBooth(shape) {
                const width = shape.width || 80;
                const height = shape.height || 80;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Main booth floor/base
                ctx.fillStyle = '#F5F5DC'; // Beige floor
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#8B4513';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Back wall panels
                ctx.fillStyle = '#E6E6FA'; // Light lavender panels
                const backPanelHeight = height * 0.8;
                ctx.fillRect(shape.x + 5, shape.y + 5, width - 10, backPanelHeight);
                ctx.strokeStyle = '#9370DB';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x + 5, shape.y + 5, width - 10, backPanelHeight);
                
                // Side panels
                const sidePanelWidth = width * 0.15;
                ctx.fillStyle = '#D8BFD8'; // Thistle color
                // Left side panel
                ctx.fillRect(shape.x, shape.y + height * 0.3, sidePanelWidth, height * 0.5);
                ctx.strokeRect(shape.x, shape.y + height * 0.3, sidePanelWidth, height * 0.5);
                // Right side panel
                ctx.fillRect(shape.x + width - sidePanelWidth, shape.y + height * 0.3, sidePanelWidth, height * 0.5);
                ctx.strokeRect(shape.x + width - sidePanelWidth, shape.y + height * 0.3, sidePanelWidth, height * 0.5);
                
                // Display counter/table in front
                ctx.fillStyle = '#8B4513'; // Brown wood
                const counterWidth = width * 0.6;
                const counterHeight = height * 0.15;
                const counterX = shape.x + (width - counterWidth) / 2;
                const counterY = shape.y + height - counterHeight - 5;
                ctx.fillRect(counterX, counterY, counterWidth, counterHeight);
                ctx.strokeStyle = '#654321';
                ctx.strokeRect(counterX, counterY, counterWidth, counterHeight);
                
                // Counter legs
                ctx.fillStyle = '#654321';
                const legWidth = 3;
                ctx.fillRect(counterX + 5, counterY + counterHeight, legWidth, 8);
                ctx.fillRect(counterX + counterWidth - 8, counterY + counterHeight, legWidth, 8);
                
                // Display screens/monitors on back wall
                ctx.fillStyle = '#000'; // Black screens
                const screenWidth = width * 0.25;
                const screenHeight = height * 0.2;
                // Left screen
                ctx.fillRect(shape.x + width * 0.15, shape.y + height * 0.2, screenWidth, screenHeight);
                // Right screen  
                ctx.fillRect(shape.x + width * 0.6, shape.y + height * 0.2, screenWidth, screenHeight);
                
                // Screen content (blue glow)
                ctx.fillStyle = '#1E90FF';
                ctx.fillRect(shape.x + width * 0.15 + 2, shape.y + height * 0.2 + 2, screenWidth - 4, screenHeight - 4);
                ctx.fillRect(shape.x + width * 0.6 + 2, shape.y + height * 0.2 + 2, screenWidth - 4, screenHeight - 4);
                
                // Booth branding/logo area (center of back wall)
                ctx.fillStyle = '#FFD700'; // Gold background for logo
                const logoWidth = width * 0.3;
                const logoHeight = height * 0.15;
                const logoX = shape.x + (width - logoWidth) / 2;
                const logoY = shape.y + height * 0.05;
                ctx.fillRect(logoX, logoY, logoWidth, logoHeight);
                ctx.strokeStyle = '#FF8C00';
                ctx.lineWidth = 2;
                ctx.strokeRect(logoX, logoY, logoWidth, logoHeight);
                
                // Company name placeholder
                ctx.fillStyle = '#000';
                ctx.font = '8px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('EXPO', logoX + logoWidth/2, logoY + logoHeight/2 + 3);
                ctx.textAlign = 'start';
                
                // Promotional materials/brochures on counter
                ctx.fillStyle = '#FFF';
                for (let i = 0; i < 3; i++) {
                    const brochureX = counterX + 10 + i * 15;
                    const brochureY = counterY - 8;
                    ctx.fillRect(brochureX, brochureY, 8, 6);
                    ctx.strokeStyle = '#CCC';
                    ctx.lineWidth = 0.5;
                    ctx.strokeRect(brochureX, brochureY, 8, 6);
                }
                
                // Entrance carpet
                ctx.fillStyle = '#DC143C'; // Red carpet
                const carpetWidth = width * 0.8;
                const carpetHeight = height * 0.1;
                ctx.fillRect(shape.x + (width - carpetWidth) / 2, shape.y + height - carpetHeight, carpetWidth, carpetHeight);
                
                // Booth lighting (ceiling spotlights representation)
                ctx.fillStyle = '#FFFF99'; // Light yellow
                for (let i = 0; i < 3; i++) {
                    const lightX = shape.x + width * 0.2 + i * width * 0.3;
                    const lightY = shape.y + 2;
                    ctx.beginPath();
                    ctx.arc(lightX, lightY, 3, 0, 2 * Math.PI);
                    ctx.fill();
                }
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawCounter(shape) {
                const width = shape.width || 120;
                const height = shape.height || 30;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Counter base/cabinet
                ctx.fillStyle = '#8B4513'; // Dark brown wood
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#654321';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Counter top surface (lighter wood)
                ctx.fillStyle = '#D2B48C'; // Tan wood top
                const topHeight = height * 0.2;
                ctx.fillRect(shape.x, shape.y, width, topHeight);
                ctx.strokeStyle = '#A0522D';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x, shape.y, width, topHeight);
                
                // Counter edge highlight (3D effect)
                ctx.fillStyle = '#F5DEB3'; // Wheat color highlight
                ctx.fillRect(shape.x, shape.y, width, 2);
                ctx.fillRect(shape.x, shape.y, 2, topHeight);
                
                // Cabinet doors/panels
                const panelWidth = width / 3;
                ctx.fillStyle = '#654321';
                ctx.strokeStyle = '#4A4A4A';
                ctx.lineWidth = 1;
                
                for (let i = 0; i < 3; i++) {
                    const panelX = shape.x + i * panelWidth;
                    const panelY = shape.y + topHeight + 2;
                    const panelH = height - topHeight - 4;
                    
                    // Panel background
                    ctx.fillRect(panelX + 2, panelY, panelWidth - 4, panelH);
                    ctx.strokeRect(panelX + 2, panelY, panelWidth - 4, panelH);
                    
                    // Panel inset detail
                    ctx.strokeStyle = '#8B4513';
                    ctx.strokeRect(panelX + 6, panelY + 4, panelWidth - 12, panelH - 8);
                    
                    // Door handle
                    ctx.fillStyle = '#C0C0C0'; // Silver handle
                    const handleX = panelX + panelWidth - 8;
                    const handleY = panelY + panelH / 2 - 2;
                    ctx.fillRect(handleX, handleY, 3, 4);
                    
                    // Handle shadow
                    ctx.fillStyle = '#808080';
                    ctx.fillRect(handleX + 1, handleY + 1, 2, 3);
                }
                
                // Counter equipment/accessories
                
                // Cash register area (left side)
                ctx.fillStyle = '#2F4F4F'; // Dark slate gray
                const registerWidth = width * 0.25;
                const registerHeight = height * 0.4;
                const registerX = shape.x + 5;
                const registerY = shape.y - registerHeight + topHeight;
                ctx.fillRect(registerX, registerY, registerWidth, registerHeight);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.strokeRect(registerX, registerY, registerWidth, registerHeight);
                
                // Cash register screen
                ctx.fillStyle = '#00FF00'; // Green LCD display
                ctx.fillRect(registerX + 3, registerY + 3, registerWidth - 6, registerHeight * 0.3);
                
                // Cash register buttons
                ctx.fillStyle = '#F5F5F5';
                for (let row = 0; row < 2; row++) {
                    for (let col = 0; col < 3; col++) {
                        const btnX = registerX + 3 + col * 6;
                        const btnY = registerY + registerHeight * 0.4 + row * 4;
                        ctx.fillRect(btnX, btnY, 4, 3);
                        ctx.strokeStyle = '#D3D3D3';
                        ctx.lineWidth = 0.5;
                        ctx.strokeRect(btnX, btnY, 4, 3);
                    }
                }
                
                // Display items/products (center area)
                ctx.fillStyle = '#FFE4B5'; // Moccasin color for products
                for (let i = 0; i < 3; i++) {
                    const itemX = shape.x + width * 0.4 + i * 15;
                    const itemY = shape.y - 8;
                    const itemW = 12;
                    const itemH = 6;
                    ctx.fillRect(itemX, itemY, itemW, itemH);
                    ctx.strokeStyle = '#DEB887';
                    ctx.lineWidth = 0.5;
                    ctx.strokeRect(itemX, itemY, itemW, itemH);
                }
                
                // Information/menu display (right side)
                ctx.fillStyle = '#F0F8FF'; // Alice blue
                const menuWidth = width * 0.2;
                const menuHeight = height * 0.6;
                const menuX = shape.x + width - menuWidth - 5;
                const menuY = shape.y - menuHeight + topHeight;
                ctx.fillRect(menuX, menuY, menuWidth, menuHeight);
                ctx.strokeStyle = '#B0C4DE';
                ctx.lineWidth = 1;
                ctx.strokeRect(menuX, menuY, menuWidth, menuHeight);
                
                // Menu lines (text representation)
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 0.5;
                for (let i = 0; i < 5; i++) {
                    const lineY = menuY + 5 + i * 4;
                    ctx.beginPath();
                    ctx.moveTo(menuX + 2, lineY);
                    ctx.lineTo(menuX + menuWidth - 2, lineY);
                    ctx.stroke();
                }
                
                // Counter support legs/base
                ctx.fillStyle = '#654321';
                const legWidth = 4;
                const legHeight = 6;
                
                // Support legs
                ctx.fillRect(shape.x + 10, shape.y + height, legWidth, legHeight);
                ctx.fillRect(shape.x + width/2 - legWidth/2, shape.y + height, legWidth, legHeight);
                ctx.fillRect(shape.x + width - 14, shape.y + height, legWidth, legHeight);
                
                // Toe kick (bottom recess)
                ctx.fillStyle = '#4A4A4A';
                ctx.fillRect(shape.x + 5, shape.y + height - 3, width - 10, 3);
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawStage(shape) {
                const width = shape.width || 120;
                const height = shape.height || 60;
                const rotationCenterX = shape.x + width/2;
                const rotationCenterY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(rotationCenterX, rotationCenterY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-rotationCenterX, -rotationCenterY);
                }
                
                // Stage platform/floor (raised surface)
                ctx.fillStyle = '#8B0000'; // Dark red stage floor
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#654321'; // Brown border
                ctx.lineWidth = 3;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Stage edge highlight (3D effect)
                ctx.fillStyle = '#A0522D'; // Saddle brown highlight
                ctx.fillRect(shape.x, shape.y, width, 4);
                ctx.fillRect(shape.x, shape.y, 4, height);
                
                // Stage curtains (theatrical red velvet)
                ctx.fillStyle = '#8B0000'; // Dark red velvet
                const curtainWidth = 12;
                const curtainHeight = height * 0.9;
                
                // Left side curtain
                ctx.fillRect(shape.x, shape.y, curtainWidth, curtainHeight);
                ctx.strokeStyle = '#4B0082'; // Indigo border
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, curtainWidth, curtainHeight);
                
                // Right side curtain
                ctx.fillRect(shape.x + width - curtainWidth, shape.y, curtainWidth, curtainHeight);
                ctx.strokeRect(shape.x + width - curtainWidth, shape.y, curtainWidth, curtainHeight);
                
                // Curtain folds/pleats
                ctx.strokeStyle = '#4B0082';
                ctx.lineWidth = 1;
                for (let i = 0; i < 3; i++) {
                    const foldX = shape.x + 2 + i * 3;
                    ctx.beginPath();
                    ctx.moveTo(foldX, shape.y);
                    ctx.lineTo(foldX, shape.y + curtainHeight);
                    ctx.stroke();
                    
                    const rightFoldX = shape.x + width - 2 - i * 3;
                    ctx.beginPath();
                    ctx.moveTo(rightFoldX, shape.y);
                    ctx.lineTo(rightFoldX, shape.y + curtainHeight);
                    ctx.stroke();
                }
                
                // Stage floor pattern (wooden planks)
                ctx.strokeStyle = '#FFD700'; // Golden lines
                ctx.lineWidth = 2;
                for (let i = 0; i < 4; i++) {
                    const y = shape.y + height - 15 - i * 12;
                    ctx.beginPath();
                    ctx.moveTo(shape.x + 15, y);
                    ctx.lineTo(shape.x + width - 15, y);
                    ctx.stroke();
                }
                
                // Stage steps/access (front)
                ctx.fillStyle = '#654321'; // Brown steps
                const stepHeight = 8;
                const stepWidth = width * 0.6;
                const stepX = shape.x + (width - stepWidth) / 2;
                
                for (let i = 0; i < 2; i++) {
                    const stepY = shape.y + height + i * stepHeight;
                    const currentStepWidth = stepWidth + i * 10;
                    const currentStepX = stepX - i * 5;
                    ctx.fillRect(currentStepX, stepY, currentStepWidth, stepHeight);
                    ctx.strokeStyle = '#8B4513';
                    ctx.lineWidth = 1;
                    ctx.strokeRect(currentStepX, stepY, currentStepWidth, stepHeight);
                }
                
                // Stage lighting (ceiling spotlights)
                ctx.fillStyle = '#FFFF99'; // Light yellow
                for (let i = 0; i < 4; i++) {
                    const lightX = shape.x + width * 0.15 + i * width * 0.25;
                    const lightY = shape.y - 5;
                    ctx.beginPath();
                    ctx.arc(lightX, lightY, 4, 0, 2 * Math.PI);
                    ctx.fill();
                    
                    // Light beam effect
                    ctx.strokeStyle = '#FFFF99';
                    ctx.lineWidth = 1;
                    ctx.globalAlpha = 0.3;
                    ctx.beginPath();
                    ctx.moveTo(lightX, lightY);
                    ctx.lineTo(lightX, shape.y + height * 0.3);
                    ctx.stroke();
                    ctx.globalAlpha = 1.0;
                }
                
                // Center stage mark (X marking the spot)
                ctx.strokeStyle = '#FFD700';
                ctx.lineWidth = 2;
                const centerX = shape.x + width / 2;
                const centerY = shape.y + height / 2;
                const markSize = 8;
                ctx.beginPath();
                ctx.moveTo(centerX - markSize, centerY - markSize);
                ctx.lineTo(centerX + markSize, centerY + markSize);
                ctx.moveTo(centerX + markSize, centerY - markSize);
                ctx.lineTo(centerX - markSize, centerY + markSize);
                ctx.stroke();
                
                // Stage backdrop (optional)
                ctx.fillStyle = '#2F4F4F'; // Dark slate gray
                ctx.fillRect(shape.x + 15, shape.y + 5, width - 30, height * 0.3);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x + 15, shape.y + 5, width - 30, height * 0.3);
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawScreen(shape) {
                const width = shape.width || 80;
                const height = shape.height || 60;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Screen bezel/frame (outer black frame)
                ctx.fillStyle = '#2F2F2F'; // Dark gray bezel
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 3;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Screen display area (inner screen)
                const screenPadding = 6;
                const screenWidth = width - screenPadding * 2;
                const screenHeight = height - screenPadding * 2;
                const screenX = shape.x + screenPadding;
                const screenY = shape.y + screenPadding;
                
                // Active screen (blue glow when on)
                ctx.fillStyle = '#1E90FF'; // Dodger blue screen
                ctx.fillRect(screenX, screenY, screenWidth, screenHeight);
                
                // Screen reflection/glass effect
                ctx.fillStyle = '#87CEEB'; // Sky blue reflection
                ctx.fillRect(screenX, screenY, screenWidth, screenHeight * 0.3);
                
                // Screen content simulation (windows/interface)
                ctx.fillStyle = '#FFF'; // White windows
                
                // Top menu bar
                ctx.fillRect(screenX + 2, screenY + 2, screenWidth - 4, 6);
                
                // Application windows
                const windowWidth = screenWidth * 0.4;
                const windowHeight = screenHeight * 0.3;
                
                // Left window
                ctx.fillRect(screenX + 4, screenY + 12, windowWidth, windowHeight);
                ctx.strokeStyle = '#C0C0C0';
                ctx.lineWidth = 1;
                ctx.strokeRect(screenX + 4, screenY + 12, windowWidth, windowHeight);
                
                // Right window
                ctx.fillRect(screenX + screenWidth - windowWidth - 4, screenY + 12, windowWidth, windowHeight);
                ctx.strokeRect(screenX + screenWidth - windowWidth - 4, screenY + 12, windowWidth, windowHeight);
                
                // Text lines in windows
                ctx.strokeStyle = '#808080';
                ctx.lineWidth = 0.5;
                for (let i = 0; i < 3; i++) {
                    const lineY = screenY + 16 + i * 3;
                    // Left window lines
                    ctx.beginPath();
                    ctx.moveTo(screenX + 6, lineY);
                    ctx.lineTo(screenX + 6 + windowWidth - 4, lineY);
                    ctx.stroke();
                    
                    // Right window lines
                    ctx.beginPath();
                    ctx.moveTo(screenX + screenWidth - windowWidth - 2, lineY);
                    ctx.lineTo(screenX + screenWidth - 6, lineY);
                    ctx.stroke();
                }
                
                // Screen stand/base
                ctx.fillStyle = '#696969'; // Dim gray stand
                const standWidth = width * 0.3;
                const standHeight = height * 0.2;
                const standX = shape.x + (width - standWidth) / 2;
                const standY = shape.y + height;
                
                // Stand neck
                ctx.fillRect(standX + standWidth * 0.4, standY, standWidth * 0.2, standHeight);
                
                // Stand base
                ctx.fillRect(standX, standY + standHeight * 0.7, standWidth, standHeight * 0.3);
                ctx.strokeStyle = '#4A4A4A';
                ctx.lineWidth = 1;
                ctx.strokeRect(standX, standY + standHeight * 0.7, standWidth, standHeight * 0.3);
                
                // Screen brand/logo (optional)
                ctx.fillStyle = '#FFF';
                ctx.font = '6px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('DISPLAY', shape.x + width/2, shape.y + height - 3);
                ctx.textAlign = 'start';
                
                // Power LED indicator
                ctx.fillStyle = '#00FF00'; // Green power LED
                ctx.beginPath();
                ctx.arc(shape.x + width - 8, shape.y + height - 8, 2, 0, 2 * Math.PI);
                ctx.fill();
                
                // Screen glare effect (diagonal highlight)
                ctx.strokeStyle = '#FFF';
                ctx.lineWidth = 1;
                ctx.globalAlpha = 0.3;
                ctx.beginPath();
                ctx.moveTo(screenX + screenWidth * 0.7, screenY);
                ctx.lineTo(screenX + screenWidth, screenY + screenHeight * 0.3);
                ctx.stroke();
                ctx.globalAlpha = 1.0;
                
                // Control buttons (on the bezel)
                ctx.fillStyle = '#404040';
                const buttonSize = 3;
                for (let i = 0; i < 3; i++) {
                    const buttonX = shape.x + width - 15 + i * 5;
                    const buttonY = shape.y + height - 15;
                    ctx.fillRect(buttonX, buttonY, buttonSize, buttonSize);
                    ctx.strokeStyle = '#606060';
                    ctx.lineWidth = 0.5;
                    ctx.strokeRect(buttonX, buttonY, buttonSize, buttonSize);
                }
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawProjector(shape) {
                const width = shape.width || 30;
                const height = shape.height || 15;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Main projector body (dark gray/black)
                ctx.fillStyle = '#2F2F2F'; // Dark gray body
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Projector top (slightly lighter)
                ctx.fillStyle = '#696969'; // Dim gray top
                ctx.fillRect(shape.x, shape.y, width, 3);
                
                // Front lens housing (circular)
                ctx.fillStyle = '#1C1C1C'; // Almost black lens housing
                const lensRadius = height * 0.4;
                const lensX = shape.x + width - lensRadius - 2;
                const lensY = shape.y + height / 2;
                ctx.beginPath();
                ctx.arc(lensX, lensY, lensRadius, 0, 2 * Math.PI);
                ctx.fill();
                ctx.strokeStyle = '#4A4A4A';
                ctx.lineWidth = 1;
                ctx.stroke();
                
                // Lens (black with blue reflection)
                ctx.fillStyle = '#000'; // Black lens
                ctx.beginPath();
                ctx.arc(lensX, lensY, lensRadius - 1, 0, 2 * Math.PI);
                ctx.fill();
                
                // Lens reflection/coating
                ctx.fillStyle = '#1E90FF'; // Blue lens coating
                ctx.globalAlpha = 0.3;
                ctx.beginPath();
                ctx.arc(lensX, lensY, lensRadius - 2, 0, 2 * Math.PI);
                ctx.fill();
                ctx.globalAlpha = 1.0;
                
                // Lens highlight
                ctx.fillStyle = '#FFF';
                ctx.globalAlpha = 0.6;
                ctx.beginPath();
                ctx.arc(lensX - 2, lensY - 2, 2, 0, 2 * Math.PI);
                ctx.fill();
                ctx.globalAlpha = 1.0;
                
                // Ventilation grilles (top)
                ctx.strokeStyle = '#4A4A4A';
                ctx.lineWidth = 1;
                for (let i = 0; i < 4; i++) {
                    const grillX = shape.x + 3 + i * 5;
                    ctx.beginPath();
                    ctx.moveTo(grillX, shape.y + 1);
                    ctx.lineTo(grillX + 3, shape.y + 1);
                    ctx.stroke();
                }
                
                // Control panel/buttons (top surface)
                ctx.fillStyle = '#404040'; // Dark buttons
                const buttonWidth = 2;
                const buttonHeight = 1.5;
                
                // Power button
                ctx.fillRect(shape.x + 4, shape.y + height - 4, buttonWidth, buttonHeight);
                
                // Menu buttons
                ctx.fillRect(shape.x + 8, shape.y + height - 4, buttonWidth, buttonHeight);
                ctx.fillRect(shape.x + 12, shape.y + height - 4, buttonWidth, buttonHeight);
                
                // Power LED (green when on)
                ctx.fillStyle = '#00FF00'; // Green LED
                ctx.beginPath();
                ctx.arc(shape.x + 3, shape.y + height - 2, 1, 0, 2 * Math.PI);
                ctx.fill();
                
                // Brand/model label
                ctx.fillStyle = '#C0C0C0';
                ctx.font = '4px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('PROJECTOR', shape.x + width/2, shape.y + height/2 - 2);
                ctx.textAlign = 'start';
                
                // Heat exhaust vents (side)
                ctx.strokeStyle = '#4A4A4A';
                ctx.lineWidth = 0.5;
                for (let i = 0; i < 3; i++) {
                    const ventY = shape.y + 4 + i * 2;
                    ctx.beginPath();
                    ctx.moveTo(shape.x + 1, ventY);
                    ctx.lineTo(shape.x + 6, ventY);
                    ctx.stroke();
                }
                
                // Projection beam (optional light effect)
                ctx.strokeStyle = '#FFFF99'; // Light yellow beam
                ctx.lineWidth = 2;
                ctx.globalAlpha = 0.3;
                ctx.beginPath();
                ctx.moveTo(lensX, lensY);
                ctx.lineTo(lensX + 40, lensY - 15); // Beam spreading upward
                ctx.moveTo(lensX, lensY);
                ctx.lineTo(lensX + 40, lensY + 15); // Beam spreading downward
                ctx.stroke();
                ctx.globalAlpha = 1.0;
                
                // Mounting/adjustment feet
                ctx.fillStyle = '#808080'; // Gray feet
                const footSize = 1.5;
                // Front feet
                ctx.fillRect(shape.x + width - 4, shape.y + height, footSize, 2);
                ctx.fillRect(shape.x + width - 4, shape.y + height, footSize, 2);
                // Back feet
                ctx.fillRect(shape.x + 2, shape.y + height, footSize, 2);
                ctx.fillRect(shape.x + 2, shape.y + height, footSize, 2);
                
                // Focus ring (around lens)
                ctx.strokeStyle = '#808080';
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.arc(lensX, lensY, lensRadius + 1, 0, 2 * Math.PI);
                ctx.stroke();
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawBanner(shape) {
                const width = shape.width || 60;
                const height = shape.height || 100;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Banner pole/support
                ctx.fillStyle = '#8B4513'; // Brown pole
                const poleWidth = 4;
                const poleHeight = height + 10;
                const poleX = shape.x - poleWidth;
                const poleY = shape.y - 5;
                ctx.fillRect(poleX, poleY, poleWidth, poleHeight);
                ctx.strokeStyle = '#654321';
                ctx.lineWidth = 1;
                ctx.strokeRect(poleX, poleY, poleWidth, poleHeight);
                
                // Pole base/stand
                ctx.fillStyle = '#696969'; // Gray base
                const baseWidth = poleWidth * 3;
                const baseHeight = 6;
                ctx.fillRect(poleX - (baseWidth - poleWidth)/2, poleY + poleHeight - baseHeight, baseWidth, baseHeight);
                
                // Banner fabric/material
                ctx.fillStyle = '#FF6347'; // Tomato red banner
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#DC143C'; // Crimson border
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Banner grommets/eyelets (attachment points)
                ctx.fillStyle = '#C0C0C0'; // Silver grommets
                const grommetRadius = 2;
                // Top grommets
                ctx.beginPath();
                ctx.arc(shape.x + 5, shape.y + 5, grommetRadius, 0, 2 * Math.PI);
                ctx.fill();
                ctx.beginPath();
                ctx.arc(shape.x + width - 5, shape.y + 5, grommetRadius, 0, 2 * Math.PI);
                ctx.fill();
                // Bottom grommets
                ctx.beginPath();
                ctx.arc(shape.x + 5, shape.y + height - 5, grommetRadius, 0, 2 * Math.PI);
                ctx.fill();
                ctx.beginPath();
                ctx.arc(shape.x + width - 5, shape.y + height - 5, grommetRadius, 0, 2 * Math.PI);
                ctx.fill();
                
                // Banner header/title area
                ctx.fillStyle = '#B22222'; // Fire brick header
                const headerHeight = height * 0.2;
                ctx.fillRect(shape.x, shape.y, width, headerHeight);
                
                // Banner title text
                ctx.fillStyle = '#FFF'; // White text
                ctx.font = 'bold 8px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('EXHIBITION', shape.x + width/2, shape.y + headerHeight/2 + 3);
                ctx.textAlign = 'start';
                
                // Banner main content area
                ctx.fillStyle = '#FFF'; // White content background
                const contentHeight = height * 0.5;
                const contentY = shape.y + headerHeight + 5;
                ctx.fillRect(shape.x + 5, contentY, width - 10, contentHeight);
                ctx.strokeStyle = '#DDD';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x + 5, contentY, width - 10, contentHeight);
                
                // Content placeholder (logo/image area)
                ctx.fillStyle = '#E0E0E0'; // Light gray placeholder
                const logoSize = Math.min(width - 20, contentHeight - 20);
                const logoX = shape.x + (width - logoSize) / 2;
                const logoY = contentY + 10;
                ctx.fillRect(logoX, logoY, logoSize, logoSize * 0.6);
                
                // Logo placeholder text
                ctx.fillStyle = '#888';
                ctx.font = '6px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('LOGO', logoX + logoSize/2, logoY + logoSize * 0.3 + 2);
                ctx.textAlign = 'start';
                
                // Banner footer/contact info
                ctx.fillStyle = '#8B0000'; // Dark red footer
                const footerHeight = height * 0.15;
                const footerY = shape.y + height - footerHeight;
                ctx.fillRect(shape.x, footerY, width, footerHeight);
                
                // Footer text
                ctx.fillStyle = '#FFF';
                ctx.font = '5px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('2024', shape.x + width/2, footerY + footerHeight/2 + 2);
                ctx.textAlign = 'start';
                
                // Banner fabric texture (subtle lines)
                ctx.strokeStyle = '#DC143C';
                ctx.lineWidth = 0.5;
                ctx.globalAlpha = 0.3;
                for (let i = 0; i < height; i += 8) {
                    ctx.beginPath();
                    ctx.moveTo(shape.x, shape.y + i);
                    ctx.lineTo(shape.x + width, shape.y + i);
                    ctx.stroke();
                }
                ctx.globalAlpha = 1.0;
                
                // Banner shadow (to show it's hanging/raised)
                ctx.fillStyle = '#000';
                ctx.globalAlpha = 0.2;
                ctx.fillRect(shape.x + 2, shape.y + 2, width, height);
                ctx.globalAlpha = 1.0;
                
                // Attachment cables/ropes
                ctx.strokeStyle = '#8B4513';
                ctx.lineWidth = 1;
                // Top attachments
                ctx.beginPath();
                ctx.moveTo(poleX + poleWidth/2, poleY + 10);
                ctx.lineTo(shape.x + 5, shape.y + 5);
                ctx.stroke();
                ctx.beginPath();
                ctx.moveTo(poleX + poleWidth/2, poleY + 10);
                ctx.lineTo(shape.x + width - 5, shape.y + 5);
                ctx.stroke();
                
                // Wind effect (slight curve to show movement)
                ctx.strokeStyle = '#FF6347';
                ctx.lineWidth = 1;
                ctx.globalAlpha = 0.5;
                ctx.beginPath();
                ctx.moveTo(shape.x + width, shape.y + height/4);
                ctx.quadraticCurveTo(shape.x + width + 3, shape.y + height/2, shape.x + width, shape.y + 3*height/4);
                ctx.stroke();
                ctx.globalAlpha = 1.0;
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawKiosk(shape) {
                const width = shape.width || 40;
                const height = shape.height || 80;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Main kiosk body (metallic silver)
                ctx.fillStyle = '#C0C0C0'; // Silver body
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#808080';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Kiosk base (wider for stability)
                ctx.fillStyle = '#A9A9A9'; // Dark gray base
                const baseWidth = width * 1.2;
                const baseHeight = height * 0.15;
                const baseX = shape.x - (baseWidth - width) / 2;
                const baseY = shape.y + height - baseHeight;
                ctx.fillRect(baseX, baseY, baseWidth, baseHeight);
                ctx.strokeRect(baseX, baseY, baseWidth, baseHeight);
                
                // Touch screen display
                ctx.fillStyle = '#000'; // Black screen border
                const screenWidth = width * 0.8;
                const screenHeight = height * 0.4;
                const screenX = shape.x + (width - screenWidth) / 2;
                const screenY = shape.y + height * 0.1;
                ctx.fillRect(screenX, screenY, screenWidth, screenHeight);
                
                // Active screen content
                ctx.fillStyle = '#1E90FF'; // Blue screen background
                const contentWidth = screenWidth * 0.9;
                const contentHeight = screenHeight * 0.9;
                const contentX = screenX + (screenWidth - contentWidth) / 2;
                const contentY = screenY + (screenHeight - contentHeight) / 2;
                ctx.fillRect(contentX, contentY, contentWidth, contentHeight);
                
                // Screen interface elements
                ctx.fillStyle = '#FFF'; // White interface elements
                
                // Top menu bar
                ctx.fillRect(contentX, contentY, contentWidth, contentHeight * 0.15);
                
                // Menu buttons/options
                const buttonWidth = contentWidth * 0.8;
                const buttonHeight = contentHeight * 0.12;
                for (let i = 0; i < 4; i++) {
                    const buttonX = contentX + (contentWidth - buttonWidth) / 2;
                    const buttonY = contentY + contentHeight * 0.25 + i * (buttonHeight + 3);
                    ctx.fillRect(buttonX, buttonY, buttonWidth, buttonHeight);
                    ctx.strokeStyle = '#C0C0C0';
                    ctx.lineWidth = 1;
                    ctx.strokeRect(buttonX, buttonY, buttonWidth, buttonHeight);
                }
                
                // Screen reflection
                ctx.fillStyle = '#FFF';
                ctx.globalAlpha = 0.2;
                ctx.fillRect(screenX, screenY, screenWidth * 0.3, screenHeight * 0.6);
                ctx.globalAlpha = 1.0;
                
                // Keypad/input area
                ctx.fillStyle = '#2F2F2F'; // Dark keypad area
                const keypadWidth = width * 0.7;
                const keypadHeight = height * 0.2;
                const keypadX = shape.x + (width - keypadWidth) / 2;
                const keypadY = shape.y + height * 0.6;
                ctx.fillRect(keypadX, keypadY, keypadWidth, keypadHeight);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.strokeRect(keypadX, keypadY, keypadWidth, keypadHeight);
                
                // Keypad buttons (3x4 grid)
                ctx.fillStyle = '#4A4A4A'; // Dark gray buttons
                const buttonSize = 3;
                const buttonSpacing = 1;
                for (let row = 0; row < 4; row++) {
                    for (let col = 0; col < 3; col++) {
                        const btnX = keypadX + 3 + col * (buttonSize + buttonSpacing);
                        const btnY = keypadY + 3 + row * (buttonSize + buttonSpacing);
                        ctx.fillRect(btnX, btnY, buttonSize, buttonSize);
                        ctx.strokeStyle = '#666';
                        ctx.lineWidth = 0.5;
                        ctx.strokeRect(btnX, btnY, buttonSize, buttonSize);
                    }
                }
                
                // Card reader slot
                ctx.fillStyle = '#000'; // Black slot
                const slotWidth = width * 0.6;
                const slotHeight = 2;
                const slotX = shape.x + (width - slotWidth) / 2;
                const slotY = shape.y + height * 0.85;
                ctx.fillRect(slotX, slotY, slotWidth, slotHeight);
                
                // Card reader label
                ctx.fillStyle = '#000';
                ctx.font = '4px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('INSERT CARD', shape.x + width/2, slotY - 2);
                ctx.textAlign = 'start';
                
                // Printer/receipt slot
                ctx.fillStyle = '#333'; // Dark gray printer area
                const printerWidth = width * 0.5;
                const printerHeight = 3;
                const printerX = shape.x + (width - printerWidth) / 2;
                const printerY = shape.y + height * 0.92;
                ctx.fillRect(printerX, printerY, printerWidth, printerHeight);
                ctx.strokeRect(printerX, printerY, printerWidth, printerHeight);
                
                // Status LED indicators
                ctx.fillStyle = '#00FF00'; // Green LED (operational)
                ctx.beginPath();
                ctx.arc(shape.x + width - 5, shape.y + 5, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                
                // Power LED
                ctx.fillStyle = '#0000FF'; // Blue LED (power)
                ctx.beginPath();
                ctx.arc(shape.x + width - 5, shape.y + 10, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                
                // Kiosk branding/logo area
                ctx.fillStyle = '#4169E1'; // Royal blue branding area
                const logoWidth = width * 0.8;
                const logoHeight = height * 0.08;
                const logoX = shape.x + (width - logoWidth) / 2;
                const logoY = shape.y + height * 0.02;
                ctx.fillRect(logoX, logoY, logoWidth, logoHeight);
                
                // Brand text
                ctx.fillStyle = '#FFF';
                ctx.font = '5px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('INFO KIOSK', shape.x + width/2, logoY + logoHeight/2 + 2);
                ctx.textAlign = 'start';
                
                // Side ventilation grilles
                ctx.strokeStyle = '#808080';
                ctx.lineWidth = 0.5;
                for (let i = 0; i < 8; i++) {
                    const ventY = shape.y + 15 + i * 8;
                    ctx.beginPath();
                    ctx.moveTo(shape.x + 1, ventY);
                    ctx.lineTo(shape.x + 4, ventY);
                    ctx.stroke();
                    
                    ctx.beginPath();
                    ctx.moveTo(shape.x + width - 4, ventY);
                    ctx.lineTo(shape.x + width - 1, ventY);
                    ctx.stroke();
                }
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawPerson(shape) {
                const width = shape.width || 20;
                const height = shape.height || 25;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Head
                ctx.fillStyle = '#FFDBAC';
                ctx.beginPath();
                ctx.arc(shape.x + width/2, shape.y + height/5, width/6, 0, 2 * Math.PI);
                ctx.fill();
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.stroke();
                
                // Body
                ctx.fillStyle = '#4169E1';
                ctx.fillRect(shape.x + width/3, shape.y + height/3, width/3, height/2);
                ctx.strokeRect(shape.x + width/3, shape.y + height/3, width/3, height/2);
                
                // Arms
                ctx.strokeStyle = '#FFDBAC';
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.moveTo(shape.x + width/3, shape.y + height/2);
                ctx.lineTo(shape.x + width/6, shape.y + 2*height/3);
                ctx.moveTo(shape.x + 2*width/3, shape.y + height/2);
                ctx.lineTo(shape.x + 5*width/6, shape.y + 2*height/3);
                ctx.stroke();
                
                // Legs
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.moveTo(shape.x + 2*width/5, shape.y + 5*height/6);
                ctx.lineTo(shape.x + width/3, shape.y + height);
                ctx.moveTo(shape.x + 3*width/5, shape.y + 5*height/6);
                ctx.lineTo(shape.x + 2*width/3, shape.y + height);
                ctx.stroke();
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawGroup(shape) {
                const width = shape.width || 60;
                const height = shape.height || 25;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Calculate proportional scaling for persons inside the group
                const baseWidth = 60; // Original group width
                const baseHeight = 25; // Original group height
                const scaleX = width / baseWidth;
                const scaleY = height / baseHeight;
                
                // Draw 3 persons with proportional scaling
                for (let i = 0; i < 3; i++) {
                    const personWidth = Math.max(15 * scaleX, 8); // Minimum 8px width
                    const personHeight = Math.max(20 * scaleY, 10); // Minimum 10px height
                    
                    // Calculate proportional spacing between persons
                    const totalPersonWidth = personWidth * 3;
                    const spacing = Math.max((width - totalPersonWidth) / 4, 2); // Minimum 2px spacing
                    
                    const personX = shape.x + spacing + i * (personWidth + spacing);
                    const personY = shape.y + (height - personHeight) / 2; // Center vertically
                    
                    const personShape = { 
                        x: personX, 
                        y: personY, 
                        width: personWidth, 
                        height: personHeight 
                    };
                    drawPerson(personShape);
                }
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawEntrance(shape) {
                const width = shape.width || 60;
                const height = shape.height || 20;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Entrance threshold/floor
                ctx.fillStyle = '#D3D3D3'; // Light gray floor
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#A9A9A9';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Door frames (double doors)
                ctx.fillStyle = '#8B4513'; // Brown door frames
                const frameWidth = 3;
                const doorWidth = (width - frameWidth * 3) / 2;
                
                // Left door frame
                ctx.fillRect(shape.x, shape.y, frameWidth, height);
                // Center frame (between doors)
                ctx.fillRect(shape.x + frameWidth + doorWidth, shape.y, frameWidth, height);
                // Right door frame
                ctx.fillRect(shape.x + width - frameWidth, shape.y, frameWidth, height);
                
                // Door panels
                ctx.fillStyle = '#DEB887'; // Burlywood doors
                
                // Left door
                const leftDoorX = shape.x + frameWidth;
                ctx.fillRect(leftDoorX, shape.y + 2, doorWidth, height - 4);
                ctx.strokeStyle = '#8B4513';
                ctx.lineWidth = 1;
                ctx.strokeRect(leftDoorX, shape.y + 2, doorWidth, height - 4);
                
                // Right door
                const rightDoorX = shape.x + frameWidth * 2 + doorWidth;
                ctx.fillRect(rightDoorX, shape.y + 2, doorWidth, height - 4);
                ctx.strokeRect(rightDoorX, shape.y + 2, doorWidth, height - 4);
                
                // Door handles
                ctx.fillStyle = '#C0C0C0'; // Silver handles
                const handleSize = 2;
                // Left door handle (right side)
                ctx.fillRect(leftDoorX + doorWidth - 4, shape.y + height/2 - 1, handleSize, handleSize);
                // Right door handle (left side)
                ctx.fillRect(rightDoorX + 2, shape.y + height/2 - 1, handleSize, handleSize);
                
                // Door glass panels (upper portion)
                ctx.fillStyle = '#E6F3FF'; // Light blue glass
                const glassHeight = height * 0.4;
                ctx.fillRect(leftDoorX + 2, shape.y + 3, doorWidth - 4, glassHeight);
                ctx.fillRect(rightDoorX + 2, shape.y + 3, doorWidth - 4, glassHeight);
                
                // Glass reflections
                ctx.fillStyle = '#FFF';
                ctx.globalAlpha = 0.3;
                ctx.fillRect(leftDoorX + 2, shape.y + 3, (doorWidth - 4) * 0.3, glassHeight);
                ctx.fillRect(rightDoorX + 2, shape.y + 3, (doorWidth - 4) * 0.3, glassHeight);
                ctx.globalAlpha = 1.0;
                
                // Welcome mat/carpet
                ctx.fillStyle = '#8B0000'; // Dark red carpet
                const matWidth = width * 0.8;
                const matHeight = 6;
                const matX = shape.x + (width - matWidth) / 2;
                const matY = shape.y + height;
                ctx.fillRect(matX, matY, matWidth, matHeight);
                ctx.strokeStyle = '#654321';
                ctx.lineWidth = 1;
                ctx.strokeRect(matX, matY, matWidth, matHeight);
                
                // Entrance signage above
                ctx.fillStyle = '#228B22'; // Forest green sign
                const signWidth = width * 0.6;
                const signHeight = 8;
                const signX = shape.x + (width - signWidth) / 2;
                const signY = shape.y - signHeight - 2;
                ctx.fillRect(signX, signY, signWidth, signHeight);
                ctx.strokeStyle = '#006400';
                ctx.lineWidth = 1;
                ctx.strokeRect(signX, signY, signWidth, signHeight);
                
                // Sign text
                ctx.fillStyle = '#FFF';
                ctx.font = 'bold 6px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('ENTRANCE', signX + signWidth/2, signY + signHeight/2 + 2);
                ctx.textAlign = 'start';
                
                // Directional arrow pointing inward
                ctx.fillStyle = '#00FF00'; // Green arrow
                const arrowSize = 8;
                const arrowX = shape.x + width/2;
                const arrowY = shape.y + height/2;
                
                // Arrow pointing inward (down)
                ctx.beginPath();
                ctx.moveTo(arrowX, arrowY - arrowSize/2);
                ctx.lineTo(arrowX - arrowSize/2, arrowY + arrowSize/2);
                ctx.lineTo(arrowX + arrowSize/2, arrowY + arrowSize/2);
                ctx.closePath();
                ctx.fill();
                
                // Arrow outline
                ctx.strokeStyle = '#006400';
                ctx.lineWidth = 1;
                ctx.stroke();
                
                // Side pillars/columns
                ctx.fillStyle = '#696969'; // Dim gray pillars
                const pillarWidth = 4;
                const pillarHeight = height + 10;
                
                // Left pillar
                ctx.fillRect(shape.x - pillarWidth - 2, shape.y - 5, pillarWidth, pillarHeight);
                ctx.strokeStyle = '#4A4A4A';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x - pillarWidth - 2, shape.y - 5, pillarWidth, pillarHeight);
                
                // Right pillar
                ctx.fillRect(shape.x + width + 2, shape.y - 5, pillarWidth, pillarHeight);
                ctx.strokeRect(shape.x + width + 2, shape.y - 5, pillarWidth, pillarHeight);
                
                // Entrance lighting
                ctx.fillStyle = '#FFFF99'; // Light yellow bulbs
                ctx.beginPath();
                ctx.arc(shape.x + width * 0.25, shape.y - 2, 2, 0, 2 * Math.PI);
                ctx.fill();
                ctx.beginPath();
                ctx.arc(shape.x + width * 0.75, shape.y - 2, 2, 0, 2 * Math.PI);
                ctx.fill();
                
                // Light glow effect
                ctx.strokeStyle = '#FFFF99';
                ctx.lineWidth = 1;
                ctx.globalAlpha = 0.3;
                ctx.beginPath();
                ctx.arc(shape.x + width * 0.25, shape.y - 2, 6, 0, 2 * Math.PI);
                ctx.stroke();
                ctx.beginPath();
                ctx.arc(shape.x + width * 0.75, shape.y - 2, 6, 0, 2 * Math.PI);
                ctx.stroke();
                ctx.globalAlpha = 1.0;
                
                // Accessibility symbol
                ctx.fillStyle = '#0000FF'; // Blue accessibility symbol
                ctx.font = 'bold 6px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('♿', shape.x + width - 8, signY + signHeight/2 + 2);
                ctx.textAlign = 'start';
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawExit(shape) {
                const width = shape.width || 60;
                const height = shape.height || 20;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Exit threshold/floor
                ctx.fillStyle = '#D3D3D3'; // Light gray floor
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#A9A9A9';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Emergency exit door (push bar style)
                ctx.fillStyle = '#CD5C5C'; // Indian red door
                const doorWidth = width * 0.8;
                const doorHeight = height - 4;
                const doorX = shape.x + (width - doorWidth) / 2;
                const doorY = shape.y + 2;
                ctx.fillRect(doorX, doorY, doorWidth, doorHeight);
                ctx.strokeStyle = '#8B0000';
                ctx.lineWidth = 2;
                ctx.strokeRect(doorX, doorY, doorWidth, doorHeight);
                
                // Door frame
                ctx.fillStyle = '#696969'; // Dim gray frame
                const frameWidth = 2;
                // Top frame
                ctx.fillRect(shape.x, shape.y, width, frameWidth);
                // Left frame
                ctx.fillRect(shape.x, shape.y, frameWidth, height);
                // Right frame
                ctx.fillRect(shape.x + width - frameWidth, shape.y, frameWidth, height);
                // Bottom frame
                ctx.fillRect(shape.x, shape.y + height - frameWidth, width, frameWidth);
                
                // Push bar (panic bar)
                ctx.fillStyle = '#C0C0C0'; // Silver push bar
                const barWidth = doorWidth * 0.8;
                const barHeight = 3;
                const barX = doorX + (doorWidth - barWidth) / 2;
                const barY = doorY + doorHeight / 2 - barHeight / 2;
                ctx.fillRect(barX, barY, barWidth, barHeight);
                ctx.strokeStyle = '#808080';
                ctx.lineWidth = 1;
                ctx.strokeRect(barX, barY, barWidth, barHeight);
                
                // Push bar ends
                ctx.fillStyle = '#A9A9A9';
                ctx.fillRect(barX - 2, barY - 1, 2, barHeight + 2);
                ctx.fillRect(barX + barWidth, barY - 1, 2, barHeight + 2);
                
                // Emergency exit sign above
                ctx.fillStyle = '#FF0000'; // Red emergency sign
                const signWidth = width * 0.7;
                const signHeight = 10;
                const signX = shape.x + (width - signWidth) / 2;
                const signY = shape.y - signHeight - 3;
                ctx.fillRect(signX, signY, signWidth, signHeight);
                ctx.strokeStyle = '#8B0000';
                ctx.lineWidth = 1;
                ctx.strokeRect(signX, signY, signWidth, signHeight);
                
                // EXIT text
                ctx.fillStyle = '#FFF';
                ctx.font = 'bold 7px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('EXIT', signX + signWidth/2, signY + signHeight/2 + 3);
                ctx.textAlign = 'start';
                
                // Running person icon
                ctx.fillStyle = '#FFF';
                ctx.font = 'bold 8px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('🏃', signX + signWidth * 0.2, signY + signHeight/2 + 3);
                ctx.textAlign = 'start';
                
                // Directional arrow pointing outward
                ctx.fillStyle = '#FFFF00'; // Yellow arrow
                const arrowSize = 6;
                const arrowX = shape.x + width/2;
                const arrowY = shape.y + height/2;
                
                // Arrow pointing outward (up)
                ctx.beginPath();
                ctx.moveTo(arrowX, arrowY + arrowSize/2);
                ctx.lineTo(arrowX - arrowSize/2, arrowY - arrowSize/2);
                ctx.lineTo(arrowX + arrowSize/2, arrowY - arrowSize/2);
                ctx.closePath();
                ctx.fill();
                
                // Arrow outline
                ctx.strokeStyle = '#FF8C00';
                ctx.lineWidth = 1;
                ctx.stroke();
                
                // Emergency lighting (illuminated exit)
                ctx.fillStyle = '#FFFF99'; // Light yellow bulbs
                ctx.beginPath();
                ctx.arc(signX + 3, signY - 2, 2, 0, 2 * Math.PI);
                ctx.fill();
                ctx.beginPath();
                ctx.arc(signX + signWidth - 3, signY - 2, 2, 0, 2 * Math.PI);
                ctx.fill();
                
                // Light glow effect
                ctx.strokeStyle = '#FFFF99';
                ctx.lineWidth = 1;
                ctx.globalAlpha = 0.4;
                ctx.beginPath();
                ctx.arc(signX + 3, signY - 2, 8, 0, 2 * Math.PI);
                ctx.stroke();
                ctx.beginPath();
                ctx.arc(signX + signWidth - 3, signY - 2, 8, 0, 2 * Math.PI);
                ctx.stroke();
                ctx.globalAlpha = 1.0;
                
                // Door hinges
                ctx.fillStyle = '#808080'; // Gray hinges
                const hingeWidth = 2;
                const hingeHeight = 4;
                // Top hinge
                ctx.fillRect(doorX - 1, doorY + 2, hingeWidth, hingeHeight);
                // Bottom hinge
                ctx.fillRect(doorX - 1, doorY + doorHeight - 6, hingeWidth, hingeHeight);
                
                // Floor safety stripe
                ctx.fillStyle = '#FFFF00'; // Yellow safety stripe
                const stripeHeight = 2;
                ctx.fillRect(shape.x, shape.y + height, width, stripeHeight);
                
                // Caution stripes (diagonal)
                ctx.strokeStyle = '#FF0000';
                ctx.lineWidth = 1;
                for (let i = 0; i < width; i += 8) {
                    ctx.beginPath();
                    ctx.moveTo(shape.x + i, shape.y + height);
                    ctx.lineTo(shape.x + i + 4, shape.y + height + stripeHeight);
                    ctx.stroke();
                }
                
                // Emergency exit sensor (above door)
                ctx.fillStyle = '#000'; // Black sensor
                const sensorWidth = 4;
                const sensorHeight = 2;
                const sensorX = shape.x + (width - sensorWidth) / 2;
                const sensorY = shape.y - 2;
                ctx.fillRect(sensorX, sensorY, sensorWidth, sensorHeight);
                
                // Sensor LED (green - operational)
                ctx.fillStyle = '#00FF00';
                ctx.beginPath();
                ctx.arc(sensorX + sensorWidth/2, sensorY + sensorHeight/2, 0.5, 0, 2 * Math.PI);
                ctx.fill();
                
                // Door closer mechanism
                ctx.fillStyle = '#4A4A4A'; // Dark gray closer
                const closerWidth = 8;
                const closerHeight = 2;
                const closerX = doorX + doorWidth - closerWidth;
                const closerY = doorY - 3;
                ctx.fillRect(closerX, closerY, closerWidth, closerHeight);
                
                // Fire safety symbol
                ctx.fillStyle = '#FF0000';
                ctx.font = 'bold 6px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('🔥', signX + signWidth * 0.8, signY + signHeight/2 + 3);
                ctx.textAlign = 'start';
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawElevator(shape) {
                const width = shape.width || 40;
                const height = shape.height || 40;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Elevator shaft/background
                ctx.fillStyle = '#2F4F4F'; // Dark slate gray shaft
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Elevator car
                ctx.fillStyle = '#C0C0C0'; // Silver car
                const carWidth = width * 0.85;
                const carHeight = height * 0.8;
                const carX = shape.x + (width - carWidth) / 2;
                const carY = shape.y + (height - carHeight) / 2;
                ctx.fillRect(carX, carY, carWidth, carHeight);
                ctx.strokeStyle = '#696969';
                ctx.lineWidth = 2;
                ctx.strokeRect(carX, carY, carWidth, carHeight);
                
                // Elevator door (double doors)
                ctx.fillStyle = '#F5F5F5'; // White doors
                const doorWidth = carWidth / 2;
                const doorHeight = carHeight;
                // Left door
                ctx.fillRect(carX, carY, doorWidth, doorHeight);
                ctx.strokeStyle = '#C0C0C0';
                ctx.lineWidth = 1;
                ctx.strokeRect(carX, carY, doorWidth, doorHeight);
                // Right door
                ctx.fillRect(carX + doorWidth, carY, doorWidth, doorHeight);
                ctx.strokeRect(carX + doorWidth, carY, doorWidth, doorHeight);
                
                // Door handles
                ctx.fillStyle = '#FFD700'; // Gold handles
                const handleWidth = 2;
                const handleHeight = 8;
                const handleX = carX + doorWidth - 3;
                const handleY = carY + doorHeight / 2 - handleHeight / 2;
                // Left door handle
                ctx.fillRect(handleX, handleY, handleWidth, handleHeight);
                // Right door handle
                ctx.fillRect(handleX + doorWidth + 1, handleY, handleWidth, handleHeight);
                
                // Door panels (decorative)
                ctx.strokeStyle = '#D3D3D3';
                ctx.lineWidth = 1;
                // Left door panel lines
                ctx.beginPath();
                ctx.moveTo(carX + 5, carY + 5);
                ctx.lineTo(carX + doorWidth - 5, carY + 5);
                ctx.moveTo(carX + 5, carY + doorHeight / 2);
                ctx.lineTo(carX + doorWidth - 5, carY + doorHeight / 2);
                ctx.moveTo(carX + 5, carY + doorHeight - 5);
                ctx.lineTo(carX + doorWidth - 5, carY + doorHeight - 5);
                ctx.stroke();
                // Right door panel lines
                ctx.beginPath();
                ctx.moveTo(carX + doorWidth + 5, carY + 5);
                ctx.lineTo(carX + carWidth - 5, carY + 5);
                ctx.moveTo(carX + doorWidth + 5, carY + doorHeight / 2);
                ctx.lineTo(carX + carWidth - 5, carY + doorHeight / 2);
                ctx.moveTo(carX + doorWidth + 5, carY + doorHeight - 5);
                ctx.lineTo(carX + carWidth - 5, carY + doorHeight - 5);
                ctx.stroke();
                
                // Elevator button panel
                ctx.fillStyle = '#000'; // Black panel
                const panelWidth = 12;
                const panelHeight = 16;
                const panelX = carX + carWidth - panelWidth - 2;
                const panelY = carY + 2;
                ctx.fillRect(panelX, panelY, panelWidth, panelHeight);
                ctx.strokeStyle = '#C0C0C0';
                ctx.lineWidth = 1;
                ctx.strokeRect(panelX, panelY, panelWidth, panelHeight);
                
                // Floor buttons
                ctx.fillStyle = '#FF0000'; // Red button
                ctx.beginPath();
                ctx.arc(panelX + panelWidth/2, panelY + 4, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                ctx.fillStyle = '#00FF00'; // Green button
                ctx.beginPath();
                ctx.arc(panelX + panelWidth/2, panelY + 8, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                ctx.fillStyle = '#0000FF'; // Blue button
                ctx.beginPath();
                ctx.arc(panelX + panelWidth/2, panelY + 12, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                
                // Floor indicator display
                ctx.fillStyle = '#00FF00'; // Green LED display
                ctx.fillRect(panelX - 8, panelY + 2, 6, 4);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.strokeRect(panelX - 8, panelY + 2, 6, 4);
                
                // Floor number (simulated)
                ctx.fillStyle = '#000';
                ctx.font = 'bold 4px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('1', panelX - 5, panelY + 5);
                ctx.textAlign = 'start';
                
                // Emergency button
                ctx.fillStyle = '#FF0000';
                ctx.beginPath();
                ctx.arc(panelX - 5, panelY + 12, 2, 0, 2 * Math.PI);
                ctx.fill();
                ctx.strokeStyle = '#8B0000';
                ctx.lineWidth = 1;
                ctx.stroke();
                
                // Emergency button text
                ctx.fillStyle = '#FFF';
                ctx.font = 'bold 3px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('SOS', panelX - 5, panelY + 14);
                ctx.textAlign = 'start';
                
                // Elevator call buttons (outside)
                ctx.fillStyle = '#000'; // Black call panel
                const callPanelWidth = 8;
                const callPanelHeight = 12;
                const callPanelX = shape.x + 2;
                const callPanelY = shape.y + 2;
                ctx.fillRect(callPanelX, callPanelY, callPanelWidth, callPanelHeight);
                ctx.strokeStyle = '#C0C0C0';
                ctx.lineWidth = 1;
                ctx.strokeRect(callPanelX, callPanelY, callPanelWidth, callPanelHeight);
                
                // Up button
                ctx.fillStyle = '#00FF00';
                ctx.beginPath();
                ctx.arc(callPanelX + callPanelWidth/2, callPanelY + 3, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                // Up arrow
                ctx.fillStyle = '#000';
                ctx.beginPath();
                ctx.moveTo(callPanelX + callPanelWidth/2, callPanelY + 1);
                ctx.lineTo(callPanelX + callPanelWidth/2 - 1, callPanelY + 3);
                ctx.lineTo(callPanelX + callPanelWidth/2 + 1, callPanelY + 3);
                ctx.closePath();
                ctx.fill();
                
                // Down button
                ctx.fillStyle = '#FF0000';
                ctx.beginPath();
                ctx.arc(callPanelX + callPanelWidth/2, callPanelY + 9, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                // Down arrow
                ctx.fillStyle = '#000';
                ctx.beginPath();
                ctx.moveTo(callPanelX + callPanelWidth/2, callPanelY + 11);
                ctx.lineTo(callPanelX + callPanelWidth/2 - 1, callPanelY + 9);
                ctx.lineTo(callPanelX + callPanelWidth/2 + 1, callPanelY + 9);
                ctx.closePath();
                ctx.fill();
                
                // Elevator logo/branding
                ctx.fillStyle = '#000';
                ctx.font = 'bold 5px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('ELEV', carX + carWidth/2, carY + carHeight + 8);
                ctx.textAlign = 'start';
                
                // Safety features
                ctx.fillStyle = '#FFFF00'; // Yellow safety strip
                ctx.fillRect(carX, carY + carHeight - 2, carWidth, 2);
                
                // Door sensors (infrared)
                ctx.fillStyle = '#00FFFF'; // Cyan sensor light
                ctx.beginPath();
                ctx.arc(carX - 1, carY + 5, 1, 0, 2 * Math.PI);
                ctx.fill();
                ctx.beginPath();
                ctx.arc(carX - 1, carY + carHeight - 5, 1, 0, 2 * Math.PI);
                ctx.fill();
                
                // Weight sensor indicator
                ctx.fillStyle = '#00FF00'; // Green weight sensor
                ctx.beginPath();
                ctx.arc(carX + carWidth + 1, carY + carHeight - 5, 1, 0, 2 * Math.PI);
                ctx.fill();
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawStairs(shape) {
                const width = shape.width || 50;
                const height = shape.height || 50;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Stairs background
                ctx.fillStyle = '#F0F0F0'; // Very light gray background
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Simple, clear step design
                const stepCount = 6;
                const stepHeight = height / stepCount;
                
                // Draw steps from bottom to top (much simpler and clearer)
                for (let i = 0; i < stepCount; i++) {
                    const stepY = shape.y + height - (i + 1) * stepHeight;
                    
                    // Step tread (horizontal surface)
                    ctx.fillStyle = '#E8E8E8'; // Light gray tread
                    ctx.fillRect(shape.x, stepY, width, stepHeight);
                    
                    // Step riser (vertical part) - darker for contrast
                    ctx.fillStyle = '#D0D0D0'; // Darker gray for depth
                    ctx.fillRect(shape.x, stepY + stepHeight * 0.8, width, stepHeight * 0.2);
                    
                    // Step edge (black line for definition)
                    ctx.strokeStyle = '#000';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(shape.x, stepY + stepHeight);
                    ctx.lineTo(shape.x + width, stepY + stepHeight);
                    ctx.stroke();
                    
                    // Step side edge (for 3D effect)
                    ctx.strokeStyle = '#999';
                    ctx.beginPath();
                    ctx.moveTo(shape.x, stepY);
                    ctx.lineTo(shape.x, stepY + stepHeight);
                    ctx.moveTo(shape.x + width, stepY);
                    ctx.lineTo(shape.x + width, stepY + stepHeight);
                    ctx.stroke();
                }
                
                // Simple handrails (just lines)
                ctx.strokeStyle = '#666'; // Dark gray handrails
                ctx.lineWidth = 2;
                
                // Left handrail
                ctx.beginPath();
                ctx.moveTo(shape.x + 3, shape.y + height);
                ctx.lineTo(shape.x + 3, shape.y + 5);
                ctx.stroke();
                
                // Right handrail
                ctx.beginPath();
                ctx.moveTo(shape.x + width - 3, shape.y + height);
                ctx.lineTo(shape.x + width - 3, shape.y + 5);
                ctx.stroke();
                
                // Direction arrow (simple and clear)
                ctx.fillStyle = '#FF0000'; // Red arrow
                const arrowSize = 12;
                const arrowX = shape.x + width/2;
                const arrowY = shape.y + height/2;
                
                // Simple up arrow
                ctx.beginPath();
                ctx.moveTo(arrowX, arrowY - arrowSize/2);
                ctx.lineTo(arrowX - arrowSize/3, arrowY + arrowSize/2);
                ctx.lineTo(arrowX + arrowSize/3, arrowY + arrowSize/2);
                ctx.closePath();
                ctx.fill();
                
                // Arrow outline for clarity
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.stroke();
                
                // Simple "STAIRS" label
                ctx.fillStyle = '#000';
                ctx.font = 'bold 6px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('STAIRS', shape.x + width/2, shape.y + height + 10);
                ctx.textAlign = 'start';
                
                // Simple step numbering (only on a few steps for clarity)
                ctx.fillStyle = '#666';
                ctx.font = '8px Arial';
                ctx.textAlign = 'center';
                for (let i = 0; i < 3; i++) {
                    const stepY = shape.y + height - (i + 1) * stepHeight;
                    ctx.fillText((i + 1).toString(), shape.x + width/2, stepY + stepHeight/2 + 2);
                }
                ctx.textAlign = 'start';
                
                // Safety yellow edge strips (simple)
                ctx.fillStyle = '#FFFF00'; // Yellow safety strips
                for (let i = 0; i < stepCount; i++) {
                    const stepY = shape.y + height - (i + 1) * stepHeight;
                    ctx.fillRect(shape.x + 2, stepY + stepHeight - 2, width - 4, 2);
                }
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawRestroom(shape) {
                const width = shape.width || 40;
                const height = shape.height || 40;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                ctx.fillStyle = '#E6E6FA';
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // WC symbol
                ctx.fillStyle = '#000';
                ctx.font = '16px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('WC', shape.x + width/2, shape.y + height/2 + 6);
                ctx.textAlign = 'start';
                
                // Door indicator
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.arc(shape.x + width/2, shape.y + height - 5, 8, 0, Math.PI);
                ctx.stroke();
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawSecurity(shape) {
                const width = shape.width || 30;
                const height = shape.height || 30;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                ctx.fillStyle = '#FFD700';
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Shield symbol
                ctx.fillStyle = '#000';
                ctx.beginPath();
                ctx.arc(shape.x + width/2, shape.y + height/2, 8, 0, 2 * Math.PI);
                ctx.stroke();
                
                // Security badge
                ctx.fillStyle = '#000';
                ctx.font = '8px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('SEC', shape.x + width/2, shape.y + height/2 + 3);
                ctx.textAlign = 'start';
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawFirstAid(shape) {
                const width = shape.width || 30;
                const height = shape.height || 30;
                const rotationCenterX = shape.x + width/2;
                const rotationCenterY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(rotationCenterX, rotationCenterY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-rotationCenterX, -rotationCenterY);
                }
                
                // First aid cabinet/station background
                ctx.fillStyle = '#FFFFFF'; // White cabinet
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#FF0000'; // Red border
                ctx.lineWidth = 3;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Red cross background circle
                ctx.fillStyle = '#FF0000'; // Red cross background
                const crossSize = Math.min(width, height) * 0.7;
                const centerX = shape.x + width / 2;
                const centerY = shape.y + height / 2;
                ctx.beginPath();
                ctx.arc(centerX, centerY, crossSize / 2, 0, 2 * Math.PI);
                ctx.fill();
                
                // White cross symbol
                ctx.fillStyle = '#FFFFFF'; // White cross
                const crossWidth = crossSize * 0.6;
                const crossThickness = crossWidth * 0.25;
                
                // Horizontal bar of cross
                ctx.fillRect(
                    centerX - crossWidth / 2, 
                    centerY - crossThickness / 2, 
                    crossWidth, 
                    crossThickness
                );
                
                // Vertical bar of cross
                ctx.fillRect(
                    centerX - crossThickness / 2, 
                    centerY - crossWidth / 2, 
                    crossThickness, 
                    crossWidth
                );
                
                // Cabinet door handle
                ctx.fillStyle = '#C0C0C0'; // Silver handle
                const handleWidth = 2;
                const handleHeight = 8;
                const handleX = shape.x + width - 4;
                const handleY = centerY - handleHeight / 2;
                ctx.fillRect(handleX, handleY, handleWidth, handleHeight);
                
                // Cabinet hinge
                ctx.fillStyle = '#808080'; // Gray hinge
                const hingeWidth = 2;
                const hingeHeight = 6;
                ctx.fillRect(shape.x + 1, shape.y + 3, hingeWidth, hingeHeight);
                ctx.fillRect(shape.x + 1, shape.y + height - 9, hingeWidth, hingeHeight);
                
                // First aid label
                ctx.fillStyle = '#000';
                ctx.font = 'bold 5px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('FIRST', centerX, shape.y + height + 8);
                ctx.fillText('AID', centerX, shape.y + height + 16);
                ctx.textAlign = 'start';
                
                // Emergency contact information
                ctx.fillStyle = '#FF0000';
                ctx.font = 'bold 4px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('911', centerX, shape.y - 5);
                ctx.textAlign = 'start';
                
                // Cabinet lock/latch
                ctx.fillStyle = '#FFD700'; // Gold lock
                const lockSize = 3;
                ctx.fillRect(handleX - 2, handleY + handleHeight + 2, lockSize, lockSize);
                ctx.strokeStyle = '#B8860B';
                ctx.lineWidth = 1;
                ctx.strokeRect(handleX - 2, handleY + handleHeight + 2, lockSize, lockSize);
                
                // Medical supplies indicators (small icons)
                ctx.fillStyle = '#FF0000';
                ctx.font = 'bold 6px Arial';
                ctx.textAlign = 'center';
                
                // Bandage symbol (top left corner)
                ctx.fillStyle = '#FFF';
                ctx.fillRect(shape.x + 2, shape.y + 2, 6, 2);
                ctx.strokeStyle = '#FF0000';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x + 2, shape.y + 2, 6, 2);
                
                // Thermometer symbol (top right corner)
                ctx.fillStyle = '#C0C0C0';
                ctx.fillRect(shape.x + width - 5, shape.y + 2, 2, 8);
                ctx.fillStyle = '#FF0000';
                ctx.beginPath();
                ctx.arc(shape.x + width - 4, shape.y + 10, 2, 0, 2 * Math.PI);
                ctx.fill();
                
                // Pills symbol (bottom left corner)
                ctx.fillStyle = '#0000FF';
                ctx.beginPath();
                ctx.arc(shape.x + 4, shape.y + height - 4, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                ctx.fillStyle = '#FF0000';
                ctx.beginPath();
                ctx.arc(shape.x + 7, shape.y + height - 6, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                
                // Scissors symbol (bottom right corner)
                ctx.strokeStyle = '#C0C0C0';
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(shape.x + width - 6, shape.y + height - 8);
                ctx.lineTo(shape.x + width - 3, shape.y + height - 5);
                ctx.moveTo(shape.x + width - 6, shape.y + height - 5);
                ctx.lineTo(shape.x + width - 3, shape.y + height - 8);
                ctx.stroke();
                
                // Emergency beacon light
                ctx.fillStyle = '#00FF00'; // Green operational light
                ctx.beginPath();
                ctx.arc(shape.x + width / 2, shape.y - 2, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                
                // Light glow effect
                ctx.strokeStyle = '#00FF00';
                ctx.lineWidth = 1;
                ctx.globalAlpha = 0.3;
                ctx.beginPath();
                ctx.arc(shape.x + width / 2, shape.y - 2, 4, 0, 2 * Math.PI);
                ctx.stroke();
                ctx.globalAlpha = 1.0;
                
                // Safety inspection sticker
                ctx.fillStyle = '#FFFF00'; // Yellow inspection sticker
                ctx.fillRect(shape.x + width - 8, shape.y + height - 12, 6, 4);
                ctx.strokeStyle = '#FFD700';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x + width - 8, shape.y + height - 12, 6, 4);
                
                // Inspection date
                ctx.fillStyle = '#000';
                ctx.font = 'bold 3px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('OK', shape.x + width - 5, shape.y + height - 9);
                ctx.textAlign = 'start';
                
                // Emergency instructions sign
                ctx.fillStyle = '#FFF';
                ctx.fillRect(shape.x - 8, shape.y + height / 2 - 6, 6, 12);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x - 8, shape.y + height / 2 - 6, 6, 12);
                
                // Instructions text
                ctx.fillStyle = '#000';
                ctx.font = 'bold 2px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('1', shape.x - 5, shape.y + height / 2 - 3);
                ctx.fillText('2', shape.x - 5, shape.y + height / 2);
                ctx.fillText('3', shape.x - 5, shape.y + height / 2 + 3);
                ctx.textAlign = 'start';
                
                // AED symbol (if applicable)
                ctx.fillStyle = '#0000FF';
                ctx.font = 'bold 4px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('AED', centerX, shape.y + height + 24);
                ctx.textAlign = 'start';
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawFireSafety(shape) {
                const width = shape.width || 30;
                const height = shape.height || 30;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Simple fire extinguisher body
                ctx.fillStyle = '#FF0000'; // Red extinguisher
                const extWidth = width * 0.6;
                const extHeight = height * 0.8;
                const extX = shape.x + (width - extWidth) / 2;
                const extY = shape.y + (height - extHeight) / 2;
                ctx.fillRect(extX, extY, extWidth, extHeight);
                ctx.strokeStyle = '#8B0000'; // Dark red outline
                ctx.lineWidth = 2;
                ctx.strokeRect(extX, extY, extWidth, extHeight);
                
                // Extinguisher top/head
                ctx.fillStyle = '#B22222'; // Darker red top
                const topHeight = extHeight * 0.2;
                ctx.fillRect(extX, extY, extWidth, topHeight);
                ctx.strokeStyle = '#8B0000';
                ctx.lineWidth = 1;
                ctx.strokeRect(extX, extY, extWidth, topHeight);
                
                // Simple pressure gauge
                ctx.fillStyle = '#FFF'; // White gauge
                const gaugeRadius = extWidth * 0.15;
                const gaugeX = extX + extWidth/2;
                const gaugeY = extY + topHeight/2;
                ctx.beginPath();
                ctx.arc(gaugeX, gaugeY, gaugeRadius, 0, 2 * Math.PI);
                ctx.fill();
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.stroke();
                
                // Handle
                ctx.fillStyle = '#000'; // Black handle
                const handleWidth = extWidth * 0.7;
                const handleHeight = 3;
                const handleX = extX + (extWidth - handleWidth) / 2;
                const handleY = extY + extHeight * 0.3;
                ctx.fillRect(handleX, handleY, handleWidth, handleHeight);
                
                // Simple hose
                ctx.fillStyle = '#000'; // Black hose
                const hoseWidth = 2;
                const hoseLength = extWidth * 0.4;
                const hoseX = extX + extWidth;
                const hoseY = extY + extHeight * 0.5;
                ctx.fillRect(hoseX, hoseY, hoseLength, hoseWidth);
                
                // Nozzle
                ctx.fillStyle = '#C0C0C0'; // Silver nozzle
                ctx.fillRect(hoseX + hoseLength, hoseY - 1, 3, 4);
                
                // Simple label
                ctx.fillStyle = '#FFF';
                ctx.font = 'bold 6px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('FIRE', shape.x + width/2, shape.y + height + 10);
                ctx.textAlign = 'start';
                
                // Simple inspection tag
                ctx.fillStyle = '#FFFF00'; // Yellow tag
                const tagSize = 4;
                ctx.fillRect(extX - 2, extY + extHeight - tagSize, tagSize, tagSize);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.strokeRect(extX - 2, extY + extHeight - tagSize, tagSize, tagSize);
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawPowerOutlet(shape) {
                const width = shape.width || 25;
                const height = shape.height || 25;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Outlet plate/faceplate background
                ctx.fillStyle = '#F8F8FF'; // Ghost white faceplate
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#C0C0C0'; // Silver border
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Outlet recessed area
                ctx.fillStyle = '#F0F0F0'; // Light gray recessed area
                const outletWidth = width * 0.8;
                const outletHeight = height * 0.6;
                const outletX = shape.x + (width - outletWidth) / 2;
                const outletY = shape.y + (height - outletHeight) / 2;
                ctx.fillRect(outletX, outletY, outletWidth, outletHeight);
                ctx.strokeStyle = '#999';
                ctx.lineWidth = 1;
                ctx.strokeRect(outletX, outletY, outletWidth, outletHeight);
                
                // Power outlet slots (standard 3-prong)
                ctx.fillStyle = '#000'; // Black slots
                
                // Left slot (hot - smaller)
                const leftSlotWidth = outletWidth * 0.15;
                const leftSlotHeight = outletHeight * 0.3;
                const leftSlotX = outletX + outletWidth * 0.25 - leftSlotWidth / 2;
                const leftSlotY = outletY + outletHeight * 0.35;
                ctx.fillRect(leftSlotX, leftSlotY, leftSlotWidth, leftSlotHeight);
                
                // Right slot (neutral - larger)
                const rightSlotWidth = outletWidth * 0.2;
                const rightSlotHeight = outletHeight * 0.3;
                const rightSlotX = outletX + outletWidth * 0.75 - rightSlotWidth / 2;
                const rightSlotY = outletY + outletHeight * 0.35;
                ctx.fillRect(rightSlotX, rightSlotY, rightSlotWidth, rightSlotHeight);
                
                // Ground slot (bottom center - round)
                const groundRadius = outletWidth * 0.08;
                const groundX = outletX + outletWidth / 2;
                const groundY = outletY + outletHeight * 0.8;
                ctx.beginPath();
                ctx.arc(groundX, groundY, groundRadius, 0, 2 * Math.PI);
                ctx.fill();
                
                // Mounting screws
                ctx.fillStyle = '#C0C0C0'; // Silver screws
                const screwRadius = width * 0.04;
                
                // Top screw
                ctx.beginPath();
                ctx.arc(shape.x + width/2, shape.y + height * 0.1, screwRadius, 0, 2 * Math.PI);
                ctx.fill();
                
                // Bottom screw
                ctx.beginPath();
                ctx.arc(shape.x + width/2, shape.y + height * 0.9, screwRadius, 0, 2 * Math.PI);
                ctx.fill();
                
                // Screw slots (Phillips head)
                ctx.strokeStyle = '#999';
                ctx.lineWidth = 0.5;
                // Top screw slot
                const topScrewX = shape.x + width/2;
                const topScrewY = shape.y + height * 0.1;
                ctx.beginPath();
                ctx.moveTo(topScrewX - screwRadius * 0.6, topScrewY);
                ctx.lineTo(topScrewX + screwRadius * 0.6, topScrewY);
                ctx.moveTo(topScrewX, topScrewY - screwRadius * 0.6);
                ctx.lineTo(topScrewX, topScrewY + screwRadius * 0.6);
                ctx.stroke();
                
                // Bottom screw slot
                const bottomScrewX = shape.x + width/2;
                const bottomScrewY = shape.y + height * 0.9;
                ctx.beginPath();
                ctx.moveTo(bottomScrewX - screwRadius * 0.6, bottomScrewY);
                ctx.lineTo(bottomScrewX + screwRadius * 0.6, bottomScrewY);
                ctx.moveTo(bottomScrewX, bottomScrewY - screwRadius * 0.6);
                ctx.lineTo(bottomScrewX, bottomScrewY + screwRadius * 0.6);
                ctx.stroke();
                
                // GFCI indicator (if applicable)
                ctx.fillStyle = '#FF0000'; // Red indicator
                const gfciX = outletX + outletWidth - 3;
                const gfciY = outletY + 2;
                ctx.beginPath();
                ctx.arc(gfciX, gfciY, 1, 0, 2 * Math.PI);
                ctx.fill();
                
                // Voltage rating label
                ctx.fillStyle = '#000';
                ctx.font = 'bold 3px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('120V', shape.x + width/2, shape.y + height + 8);
                ctx.textAlign = 'start';
                
                // Ground symbol
                ctx.fillStyle = '#00AA00'; // Green ground
                ctx.font = 'bold 4px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('⏚', shape.x + width/2, shape.y - 3);
                ctx.textAlign = 'start';
                
                // Safety shutters (modern outlets)
                ctx.fillStyle = '#E0E0E0'; // Light gray shutters
                const shutterWidth = leftSlotWidth * 0.8;
                const shutterHeight = 1;
                
                // Left shutter
                ctx.fillRect(leftSlotX + (leftSlotWidth - shutterWidth)/2, leftSlotY + leftSlotHeight/2, shutterWidth, shutterHeight);
                
                // Right shutter
                ctx.fillRect(rightSlotX + (rightSlotWidth - shutterWidth)/2, rightSlotY + rightSlotHeight/2, shutterWidth, shutterHeight);
                
                // Outlet brand/UL listing mark
                ctx.fillStyle = '#666';
                ctx.font = 'bold 2px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('UL', outletX + 3, outletY + outletHeight - 2);
                ctx.textAlign = 'start';
                
                // Power indicator LED (if smart outlet)
                ctx.fillStyle = '#00FF00'; // Green power LED
                ctx.beginPath();
                ctx.arc(outletX + outletWidth - 2, outletY + outletHeight - 2, 0.5, 0, 2 * Math.PI);
                ctx.fill();
                
                // Cord/plug shadow (if something is plugged in)
                ctx.fillStyle = '#D3D3D3'; // Light gray shadow
                ctx.globalAlpha = 0.5;
                const plugWidth = outletWidth * 0.6;
                const plugHeight = 4;
                const plugX = outletX + (outletWidth - plugWidth) / 2;
                const plugY = outletY + outletHeight + 1;
                ctx.fillRect(plugX, plugY, plugWidth, plugHeight);
                ctx.globalAlpha = 1.0;
                
                // Outlet protection cover (when not in use)
                ctx.fillStyle = '#FFF'; // White safety cover
                ctx.globalAlpha = 0.8;
                const coverWidth = outletWidth * 0.3;
                const coverHeight = outletHeight * 0.2;
                const coverX = outletX + outletWidth * 0.1;
                const coverY = outletY + outletHeight * 0.7;
                ctx.fillRect(coverX, coverY, coverWidth, coverHeight);
                ctx.strokeStyle = '#CCC';
                ctx.lineWidth = 0.5;
                ctx.strokeRect(coverX, coverY, coverWidth, coverHeight);
                ctx.globalAlpha = 1.0;
                
                // Electrical box outline (behind the faceplate)
                ctx.strokeStyle = '#999';
                ctx.lineWidth = 1;
                ctx.globalAlpha = 0.3;
                ctx.strokeRect(shape.x - 2, shape.y - 2, width + 4, height + 4);
                ctx.globalAlpha = 1.0;
                
                // Wire nuts (visible in electrical box)
                ctx.fillStyle = '#FFD700'; // Yellow wire nut
                ctx.beginPath();
                ctx.arc(shape.x - 1, shape.y + height/3, 1, 0, 2 * Math.PI);
                ctx.fill();
                
                ctx.fillStyle = '#FF0000'; // Red wire nut
                ctx.beginPath();
                ctx.arc(shape.x - 1, shape.y + 2*height/3, 1, 0, 2 * Math.PI);
                ctx.fill();
                
                // Circuit breaker reference
                ctx.fillStyle = '#000';
                ctx.font = 'bold 2px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('15A', shape.x + width + 8, shape.y + height/2);
                ctx.textAlign = 'start';
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            // Outdoor element drawing functions
            function drawTree(shape) {
                const width = shape.width || 30;
                const height = shape.height || 40;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Tree trunk
                ctx.fillStyle = '#8B4513'; // Brown trunk
                const trunkWidth = width * 0.3;
                const trunkHeight = height * 0.4;
                const trunkX = shape.x + (width - trunkWidth) / 2;
                const trunkY = shape.y + height - trunkHeight;
                ctx.fillRect(trunkX, trunkY, trunkWidth, trunkHeight);
                ctx.strokeStyle = '#654321';
                ctx.lineWidth = 1;
                ctx.strokeRect(trunkX, trunkY, trunkWidth, trunkHeight);
                
                // Tree foliage (multiple circles for natural look)
                ctx.fillStyle = '#228B22'; // Forest green
                const foliageRadius = width * 0.4;
                const foliageX = shape.x + width/2;
                const foliageY = shape.y + height * 0.3;
                
                // Main foliage
                ctx.beginPath();
                ctx.arc(foliageX, foliageY, foliageRadius, 0, 2 * Math.PI);
                ctx.fill();
                
                // Additional foliage layers for depth
                ctx.fillStyle = '#32CD32'; // Lime green
                ctx.beginPath();
                ctx.arc(foliageX - foliageRadius * 0.3, foliageY - foliageRadius * 0.2, foliageRadius * 0.6, 0, 2 * Math.PI);
                ctx.fill();
                
                ctx.beginPath();
                ctx.arc(foliageX + foliageRadius * 0.3, foliageY - foliageRadius * 0.1, foliageRadius * 0.5, 0, 2 * Math.PI);
                ctx.fill();
                
                // Tree shadow
                ctx.fillStyle = 'rgba(0, 0, 0, 0.2)';
                ctx.beginPath();
                ctx.ellipse(shape.x + width/2 + 2, shape.y + height + 2, width * 0.4, height * 0.1, 0, 0, 2 * Math.PI);
                ctx.fill();
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawRoad(shape) {
                const width = shape.width || 40;
                const height = shape.height || 20;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Road surface
                ctx.fillStyle = '#696969'; // Dark gray asphalt
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Road markings (center line)
                ctx.strokeStyle = '#FFF'; // White lines
                ctx.lineWidth = 2;
                ctx.setLineDash([5, 5]); // Dashed center line
                ctx.beginPath();
                ctx.moveTo(shape.x, shape.y + height/2);
                ctx.lineTo(shape.x + width, shape.y + height/2);
                ctx.stroke();
                ctx.setLineDash([]); // Reset line dash
                
                // Road edge lines
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(shape.x, shape.y + 2);
                ctx.lineTo(shape.x + width, shape.y + 2);
                ctx.stroke();
                
                ctx.beginPath();
                ctx.moveTo(shape.x, shape.y + height - 2);
                ctx.lineTo(shape.x + width, shape.y + height - 2);
                ctx.stroke();
                
                // Road texture (small dots for asphalt)
                ctx.fillStyle = '#555';
                for (let i = 0; i < width; i += 3) {
                    for (let j = 0; j < height; j += 3) {
                        if (Math.random() > 0.7) {
                            ctx.fillRect(shape.x + i, shape.y + j, 1, 1);
                        }
                    }
                }
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawBuilding(shape) {
                const width = shape.width || 35;
                const height = shape.height || 45;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Building base
                ctx.fillStyle = '#C0C0C0'; // Silver/gray building
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#696969';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Building roof
                ctx.fillStyle = '#8B4513'; // Brown roof
                const roofHeight = height * 0.15;
                ctx.fillRect(shape.x - 2, shape.y, width + 4, roofHeight);
                ctx.strokeStyle = '#654321';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x - 2, shape.y, width + 4, roofHeight);
                
                // Windows
                ctx.fillStyle = '#87CEEB'; // Sky blue windows
                const windowWidth = width * 0.2;
                const windowHeight = height * 0.15;
                const windowSpacing = width * 0.15;
                
                // First floor windows
                for (let i = 0; i < 2; i++) {
                    const windowX = shape.x + windowSpacing + (i * (windowWidth + windowSpacing));
                    const windowY = shape.y + height * 0.3;
                    ctx.fillRect(windowX, windowY, windowWidth, windowHeight);
                    ctx.strokeStyle = '#000';
                    ctx.lineWidth = 1;
                    ctx.strokeRect(windowX, windowY, windowWidth, windowHeight);
                    
                    // Window frames
                    ctx.strokeStyle = '#FFF';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(windowX + windowWidth/2, windowY);
                    ctx.lineTo(windowX + windowWidth/2, windowY + windowHeight);
                    ctx.stroke();
                }
                
                // Second floor windows
                for (let i = 0; i < 2; i++) {
                    const windowX = shape.x + windowSpacing + (i * (windowWidth + windowSpacing));
                    const windowY = shape.y + height * 0.6;
                    ctx.fillRect(windowX, windowY, windowWidth, windowHeight);
                    ctx.strokeStyle = '#000';
                    ctx.lineWidth = 1;
                    ctx.strokeRect(windowX, windowY, windowWidth, windowHeight);
                    
                    // Window frames
                    ctx.strokeStyle = '#FFF';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(windowX + windowWidth/2, windowY);
                    ctx.lineTo(windowX + windowWidth/2, windowY + windowHeight);
                    ctx.stroke();
                }
                
                // Main entrance door
                ctx.fillStyle = '#8B4513'; // Brown door
                const doorWidth = width * 0.3;
                const doorHeight = height * 0.25;
                const doorX = shape.x + (width - doorWidth) / 2;
                const doorY = shape.y + height - doorHeight;
                ctx.fillRect(doorX, doorY, doorWidth, doorHeight);
                ctx.strokeStyle = '#654321';
                ctx.lineWidth = 2;
                ctx.strokeRect(doorX, doorY, doorWidth, doorHeight);
                
                // Door handle
                ctx.fillStyle = '#FFD700'; // Gold handle
                const handleSize = 2;
                const handleX = doorX + doorWidth - 3;
                const handleY = doorY + doorHeight/2;
                ctx.beginPath();
                ctx.arc(handleX, handleY, handleSize, 0, 2 * Math.PI);
                ctx.fill();
                
                // Building shadow
                ctx.fillStyle = 'rgba(0, 0, 0, 0.3)';
                ctx.fillRect(shape.x + 2, shape.y + height + 2, width, height * 0.1);
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawParking(shape) {
                const width = shape.width || 40;
                const height = shape.height || 30;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Parking lot surface
                ctx.fillStyle = '#696969'; // Dark gray asphalt
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Parking space lines
                ctx.strokeStyle = '#FFF'; // White lines
                ctx.lineWidth = 2;
                const spaceWidth = width / 3;
                
                // Vertical dividing lines
                for (let i = 1; i < 3; i++) {
                    ctx.beginPath();
                    ctx.moveTo(shape.x + i * spaceWidth, shape.y);
                    ctx.lineTo(shape.x + i * spaceWidth, shape.y + height);
                    ctx.stroke();
                }
                
                // Parking space numbers
                ctx.fillStyle = '#FFF';
                ctx.font = 'bold 8px Arial';
                ctx.textAlign = 'center';
                for (let i = 0; i < 3; i++) {
                    const spaceX = shape.x + i * spaceWidth + spaceWidth/2;
                    const spaceY = shape.y + height/2 + 3;
                    ctx.fillText((i + 1).toString(), spaceX, spaceY);
                }
                ctx.textAlign = 'start';
                
                // Parking sign
                ctx.fillStyle = '#000080'; // Navy blue sign
                const signWidth = 8;
                const signHeight = 6;
                const signX = shape.x + width/2 - signWidth/2;
                const signY = shape.y - signHeight - 2;
                ctx.fillRect(signX, signY, signWidth, signHeight);
                ctx.strokeStyle = '#FFF';
                ctx.lineWidth = 1;
                ctx.strokeRect(signX, signY, signWidth, signHeight);
                
                // P letter on sign
                ctx.fillStyle = '#FFF';
                ctx.font = 'bold 6px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('P', signX + signWidth/2, signY + signHeight/2 + 2);
                ctx.textAlign = 'start';
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawFountain(shape) {
                const width = shape.width || 30;
                const height = shape.height || 35;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Fountain base
                ctx.fillStyle = '#C0C0C0'; // Silver base
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#696969';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Fountain center column
                ctx.fillStyle = '#FFF'; // White marble
                const columnWidth = width * 0.4;
                const columnHeight = height * 0.6;
                const columnX = shape.x + (width - columnWidth) / 2;
                const columnY = shape.y + height * 0.2;
                ctx.fillRect(columnX, columnY, columnWidth, columnHeight);
                ctx.strokeStyle = '#DDD';
                ctx.lineWidth = 1;
                ctx.strokeRect(columnX, columnY, columnWidth, columnHeight);
                
                // Fountain top bowl
                ctx.fillStyle = '#87CEEB'; // Light blue water
                const bowlWidth = width * 0.6;
                const bowlHeight = height * 0.15;
                const bowlX = shape.x + (width - bowlWidth) / 2;
                const bowlY = shape.y + height * 0.15;
                ctx.beginPath();
                ctx.arc(bowlX + bowlWidth/2, bowlY + bowlHeight/2, bowlWidth/2, 0, 2 * Math.PI);
                ctx.fill();
                ctx.strokeStyle = '#4682B4';
                ctx.lineWidth = 1;
                ctx.stroke();
                
                // Water spout
                ctx.fillStyle = '#87CEEB';
                const spoutWidth = 2;
                const spoutHeight = height * 0.1;
                const spoutX = shape.x + width/2 - spoutWidth/2;
                const spoutY = shape.y + height * 0.25;
                ctx.fillRect(spoutX, spoutY, spoutWidth, spoutHeight);
                
                // Water droplets
                ctx.fillStyle = '#87CEEB';
                for (let i = 0; i < 3; i++) {
                    const dropX = spoutX + (i - 1) * 3;
                    const dropY = spoutY + spoutHeight + 2;
                    ctx.beginPath();
                    ctx.arc(dropX, dropY, 1, 0, 2 * Math.PI);
                    ctx.fill();
                }
                
                // Fountain label
                ctx.fillStyle = '#000';
                ctx.font = 'bold 6px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('FOUNTAIN', shape.x + width/2, shape.y + height + 10);
                ctx.textAlign = 'start';
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawGarden(shape) {
                const width = shape.width || 35;
                const height = shape.height || 30;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Garden soil base
                ctx.fillStyle = '#8B4513'; // Brown soil
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#654321';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Garden border
                ctx.strokeStyle = '#228B22'; // Green border
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Flowers (various colors)
                const flowerColors = ['#FF0000', '#FF69B4', '#FFD700', '#FF4500', '#9370DB'];
                const flowerCount = 8;
                
                for (let i = 0; i < 8; i++) {
                    const flowerX = shape.x + 5 + (i % 3) * (width / 3);
                    const flowerY = shape.y + 5 + Math.floor(i / 3) * (height / 3);
                    
                    // Flower petals
                    ctx.fillStyle = flowerColors[i % flowerColors.length];
                    const petalRadius = 2;
                    ctx.beginPath();
                    ctx.arc(flowerX, flowerY, petalRadius, 0, 2 * Math.PI);
                    ctx.fill();
                    
                    // Flower center
                    ctx.fillStyle = '#FFD700'; // Gold center
                    ctx.beginPath();
                    ctx.arc(flowerX, flowerY, 1, 0, 2 * Math.PI);
                    ctx.fill();
                }
                
                // Small trees/bushes
                ctx.fillStyle = '#228B22'; // Green bushes
                for (let i = 0; i < 3; i++) {
                    const bushX = shape.x + 8 + i * (width / 4);
                    const bushY = shape.y + height - 8;
                    const bushRadius = 3;
                    ctx.beginPath();
                    ctx.arc(bushX, bushY, bushRadius, 0, 2 * Math.PI);
                    ctx.fill();
                }
                
                // Garden path
                ctx.fillStyle = '#DEB887'; // Tan path
                const pathWidth = 3;
                const pathX = shape.x + width/2 - pathWidth/2;
                ctx.fillRect(pathX, shape.y, pathWidth, height);
                
                // Garden label
                ctx.fillStyle = '#000';
                ctx.font = 'bold 6px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('GARDEN', shape.x + width/2, shape.y + height + 10);
                ctx.textAlign = 'start';
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            // Add shape function
            function addShape(type, subtype = null) {
                let shape = {
                    type: subtype || type,
                    x: 50 + Math.random() * 100,
                    y: 50 + Math.random() * 100,
                    fillColor: document.getElementById('fillColor').value,
                    strokeColor: document.getElementById('strokeColor').value,
                    borderWidth: parseInt(document.getElementById('borderWidth').value),
                    rotation: 0, // Add rotation property
                    id: Date.now(),
                    // Booking properties
                    bookable: false, // Default to non-bookable
                    maxCapacity: 1, // Default capacity
                    label: '', // Custom label
                    itemName: '', // Display name
                    price: null // Custom price (null means use default)
                };
                
                // Set default dimensions based on type
                switch(shape.type) {
                    case 'rectangle':
                        shape.width = 100;
                        shape.height = 80;
                        break;
                    case 'circle':
                        shape.radius = 30;
                        break;
                    case 'triangle':
                    case 'pentagon':
                    case 'hexagon':
                        shape.size = 40;
                        shape.width = shape.size;
                        shape.height = shape.size;
                        break;
                    case 'arrow':
                        shape.width = 80;
                        shape.height = 20;
                        shape.bookable = false; // Arrow is not bookable
                        break;
                    case 'line':
                        shape.width = 80;
                        shape.height = 2;
                        shape.bookable = false; // Line is not bookable
                        break;
                    case 'chair':
                        shape.width = 25;
                        shape.height = 25;
                        break;
                    case 'table':
                        shape.width = 80;
                        shape.height = 40;
                        shape.bookable = true;
                        shape.maxCapacity = defaultBoothCapacity;
                        shape.price = defaultPrice;
                        if (enableAutoLabeling) {
                            boothCounter++;
                            shape.label = labelPrefix + (startingLabelNumber + boothCounter - 1);
                        }
                        break;
                    case 'desk':
                        shape.width = 100;
                        shape.height = 60;
                        shape.bookable = true;
                        shape.maxCapacity = defaultBoothCapacity;
                        shape.price = defaultPrice;
                        if (enableAutoLabeling) {
                            boothCounter++;
                            shape.label = labelPrefix + (startingLabelNumber + boothCounter - 1);
                        }
                        break;
                    case 'booth':
                        shape.width = 80;
                        shape.height = 80;
                        shape.bookable = true;
                        shape.maxCapacity = defaultBoothCapacity;
                        shape.price = defaultPrice;
                        if (enableAutoLabeling) {
                            boothCounter++;
                            shape.label = labelPrefix + (startingLabelNumber + boothCounter - 1);
                        }
                        break;
                    case 'counter':
                        shape.width = 120;
                        shape.height = 30;
                        break;
                    case 'stage':
                        shape.width = 120;
                        shape.height = 60;
                        shape.bookable = false; // Stage is not bookable
                        break;
                    case 'screen':
                        shape.width = 80;
                        shape.height = 60;
                        break;
                    case 'banner':
                        shape.width = 60;
                        shape.height = 100;
                        break;
                    case 'entrance':
                    case 'exit':
                        shape.width = 60;
                        shape.height = 20;
                        break;
                    case 'person':
                        shape.width = 20;
                        shape.height = 25;
                        break;
                    case 'group':
                        shape.width = 45;
                        shape.height = 25;
                        break;
                    case 'projector':
                        shape.width = 30;
                        shape.height = 15;
                        break;
                    case 'kiosk':
                        shape.width = 40;
                        shape.height = 80;
                        break;
                    case 'elevator':
                        shape.width = 40;
                        shape.height = 40;
                        break;
                    case 'stairs':
                        shape.width = 50;
                        shape.height = 50;
                        break;
                    case 'restroom':
                        shape.width = 40;
                        shape.height = 40;
                        shape.bookable = false; // Utilities are not bookable
                        break;
                    case 'security':
                    case 'firstaid':
                    case 'fire':
                        shape.width = 30;
                        shape.height = 30;
                        shape.bookable = false; // Utilities are not bookable
                        break;
                    case 'power':
                        shape.width = 25;
                        shape.height = 25;
                        shape.bookable = false; // Utilities are not bookable
                        break;
                    // Outdoor elements
                    case 'tree':
                        shape.width = 30;
                        shape.height = 40;
                        shape.bookable = false; // Outdoor elements are not bookable
                        break;
                    case 'road':
                        shape.width = 40;
                        shape.height = 20;
                        shape.bookable = false; // Outdoor elements are not bookable
                        break;
                    case 'building':
                        shape.width = 35;
                        shape.height = 45;
                        shape.bookable = false; // Outdoor elements are not bookable
                        break;
                    case 'parking':
                        shape.width = 40;
                        shape.height = 30;
                        shape.bookable = false; // Outdoor elements are not bookable
                        break;
                    case 'fountain':
                        shape.width = 30;
                        shape.height = 35;
                        shape.bookable = false; // Outdoor elements are not bookable
                        break;
                    case 'garden':
                        shape.width = 35;
                        shape.height = 30;
                        shape.bookable = false; // Outdoor elements are not bookable
                        break;
                    default:
                        shape.size = shape.size || 30;
                        break;
                }
                
                shapes.push(shape);
                redrawCanvas();
                hideInstructions();
                trackChanges();
            }
            
            // Add text
            function addText() {
                const text = prompt('Enter text:');
                if (text) {
                    const shape = {
                        type: 'text',
                        x: 100,
                        y: 100,
                        width: 120, // Default width for text
                        height: 30,  // Default height for text
                        text: text,
                        textColor: document.getElementById('textColor').value,
                        fontSize: parseInt(document.getElementById('fontSize').value),
                        fontFamily: document.getElementById('fontFamily').value,
                        rotation: 0, // Add rotation property
                        id: Date.now()
                    };
                    shapes.push(shape);
                    redrawCanvas();
                    hideInstructions();
                    trackChanges();
                }
            }
            
            // Hide instructions when shapes are added
            function hideInstructions() {
                toggleCanvasInstructions();
            }
            
            // Mouse events
            canvas.addEventListener('mousedown', function(e) {
                const rect = canvas.getBoundingClientRect();
                startX = e.clientX - rect.left;
                startY = e.clientY - rect.top;
                isDrawing = true;
                
                // First check if clicking on a resize handle of the currently selected shape
                if (selectedShape) {
                    // Check for rotation handle first
                    if (isRotationHandleClicked(startX, startY, selectedShape)) {
                        isRotating = true;
                        // Calculate rotation center
                        let centerX, centerY;
                        if (selectedShape.type === 'circle') {
                            centerX = selectedShape.x + selectedShape.radius;
                            centerY = selectedShape.y + selectedShape.radius;
                        } else if (selectedShape.type === 'triangle' || selectedShape.type === 'pentagon' || selectedShape.type === 'hexagon') {
                            centerX = selectedShape.x + selectedShape.size / 2;
                            centerY = selectedShape.y + selectedShape.size / 2;
                        } else {
                            centerX = selectedShape.x + (selectedShape.width || 40) / 2;
                            centerY = selectedShape.y + (selectedShape.height || 40) / 2;
                        }
                        rotationCenter = { x: centerX, y: centerY };
                        selectedShape._originalRotation = selectedShape.rotation || 0;
                        // Store initial angle from center to mouse
                        const initialDeltaX = startX - centerX;
                        const initialDeltaY = startY - centerY;
                        selectedShape._initialAngle = Math.atan2(initialDeltaY, initialDeltaX) * 180 / Math.PI;
                    } else {
                        resizeHandle = getResizeHandle(startX, startY, selectedShape);
                        if (resizeHandle) {
                            isResizing = true;
                            // Store original dimensions for resizing
                            selectedShape._originalWidth = selectedShape.width || selectedShape.size || (selectedShape.radius ? selectedShape.radius * 2 : 40);
                            selectedShape._originalHeight = selectedShape.height || selectedShape.size || (selectedShape.radius ? selectedShape.radius * 2 : 40);
                            selectedShape._originalX = selectedShape.x;
                            selectedShape._originalY = selectedShape.y;
                        } else {
                            // Check if clicking on the shape body for moving
                            if (isPointInShape(startX, startY, selectedShape)) {
                                // Calculate drag offset if shape is selected (for moving)
                                dragOffsetX = startX - selectedShape.x;
                                dragOffsetY = startY - selectedShape.y;
                            } else {
                                // Clicking outside the selected shape - check for other shapes
                                const clickedShape = getShapeAt(startX, startY);
                                if (clickedShape && clickedShape !== selectedShape) {
                                    // Select the new shape
                                    selectedShape = clickedShape;
                                    dragOffsetX = startX - selectedShape.x;
                                    dragOffsetY = startY - selectedShape.y;
                                } else if (!clickedShape) {
                                    // Clicking on empty canvas - deselect current shape
                                    selectedShape = null;
                                    // Hide item properties panel
                                    itemPropertiesPanel.style.display = 'none';
                                }
                            }
                        }
                    }
                } else {
                    // No shape selected, check for new shape selection
                    const clickedShape = getShapeAt(startX, startY);
                    if (clickedShape) {
                        selectedShape = clickedShape;
                        // Calculate drag offset for moving
                        dragOffsetX = startX - selectedShape.x;
                        dragOffsetY = startY - selectedShape.y;
                    }
                }
                
                // Redraw to show/hide selection handles
                redrawCanvas();
                
                // Force another redraw after a small delay to ensure handles are visible
                if (selectedShape) {
                    setTimeout(() => {
                        redrawCanvas();
                    }, 10);
                }
            });
            
            canvas.addEventListener('mousemove', function(e) {
                const rect = canvas.getBoundingClientRect();
                const currentX = e.clientX - rect.left;
                const currentY = e.clientY - rect.top;
                
                if (!isDrawing) {
                    // Handle cursor changes when not drawing - prioritize resize handles
                    let cursorSet = false;
                    
                    // First check for rotation handle on selected shape
                    if (selectedShape && isRotationHandleClicked(currentX, currentY, selectedShape)) {
                        canvas.style.cursor = 'grab';
                        cursorSet = true;
                    } else if (selectedShape) {
                        // Then check for resize handles
                        const handle = getResizeHandle(currentX, currentY, selectedShape);
                        if (handle) {
                            const cursors = {
                                'nw': 'nw-resize', 'n': 'n-resize', 'ne': 'ne-resize',
                                'e': 'e-resize', 'se': 'se-resize', 's': 's-resize',
                                'sw': 'sw-resize', 'w': 'w-resize'
                            };
                            canvas.style.cursor = cursors[handle];
                            cursorSet = true;
                        }
                    }
                    
                    // If no resize handle, check for shapes
                    if (!cursorSet) {
                        const shape = getShapeAt(currentX, currentY);
                        if (shape) {
                            canvas.style.cursor = 'move';
                        } else {
                            canvas.style.cursor = 'crosshair';
                        }
                    }
                    return;
                }
                
                if (selectedShape) {
                    if (isRotating && rotationCenter) {
                        // Handle rotation - calculate relative angle change
                        const deltaX = currentX - rotationCenter.x;
                        const deltaY = currentY - rotationCenter.y;
                        const currentAngle = Math.atan2(deltaY, deltaX) * 180 / Math.PI;
                        
                        // Calculate the difference from the initial angle
                        let angleDifference = currentAngle - selectedShape._initialAngle;
                        
                        // Add the difference to the original rotation
                        let newRotation = selectedShape._originalRotation + angleDifference;
                        
                        // Normalize angle to 0-360 degrees
                        while (newRotation < 0) newRotation += 360;
                        while (newRotation >= 360) newRotation -= 360;
                        
                        selectedShape.rotation = newRotation;
                        redrawCanvas();
                        trackChanges();
                    } else if (isResizing && resizeHandle) {
                        // Handle resizing with smooth continuous updates
                        const minSize = 10; // Minimum size for shapes
                        const deltaX = currentX - startX;
                        const deltaY = currentY - startY;
                        
                        // Store original dimensions for proper resizing
                        if (!selectedShape._originalWidth) {
                            selectedShape._originalWidth = selectedShape.width || selectedShape.size || (selectedShape.radius ? selectedShape.radius * 2 : 40);
                            selectedShape._originalHeight = selectedShape.height || selectedShape.size || (selectedShape.radius ? selectedShape.radius * 2 : 40);
                            selectedShape._originalX = selectedShape.x;
                            selectedShape._originalY = selectedShape.y;
                        }
                        
                        switch (resizeHandle) {
                            case 'se': // Southeast handle - resize width and height
                                if (selectedShape.type === 'circle') {
                                    const newRadius = Math.max(minSize/2, selectedShape._originalWidth/2 + deltaX/2);
                                    selectedShape.radius = newRadius;
                                } else if (selectedShape.width && selectedShape.height) {
                                    selectedShape.width = Math.max(minSize, selectedShape._originalWidth + deltaX);
                                    selectedShape.height = Math.max(minSize, selectedShape._originalHeight + deltaY);
                                } else if (selectedShape.size) {
                                    const newSize = Math.max(minSize, selectedShape._originalWidth + Math.max(deltaX, deltaY));
                                    selectedShape.size = newSize;
                                    selectedShape.width = newSize;
                                    selectedShape.height = newSize;
                                }
                                break;
                                
                            case 'e': // East handle - resize width only
                                if (selectedShape.type === 'circle') {
                                    const newRadius = Math.max(minSize/2, selectedShape._originalWidth/2 + deltaX/2);
                                    selectedShape.radius = newRadius;
                                } else if (selectedShape.type === 'triangle' || selectedShape.type === 'pentagon' || selectedShape.type === 'hexagon') {
                                    const newSize = Math.max(minSize, selectedShape._originalWidth + deltaX);
                                    selectedShape.size = newSize;
                                    selectedShape.width = newSize;
                                    selectedShape.height = newSize;
                                } else if (selectedShape.width) {
                                    selectedShape.width = Math.max(minSize, selectedShape._originalWidth + deltaX);
                                }
                                break;
                                
                            case 's': // South handle - resize height only
                                if (selectedShape.type === 'circle') {
                                    const newRadius = Math.max(minSize/2, selectedShape._originalHeight/2 + deltaY/2);
                                    selectedShape.radius = newRadius;
                                } else if (selectedShape.type === 'triangle' || selectedShape.type === 'pentagon' || selectedShape.type === 'hexagon') {
                                    const newSize = Math.max(minSize, selectedShape._originalHeight + deltaY);
                                    selectedShape.size = newSize;
                                    selectedShape.width = newSize;
                                    selectedShape.height = newSize;
                                } else if (selectedShape.height) {
                                    selectedShape.height = Math.max(minSize, selectedShape._originalHeight + deltaY);
                                }
                                break;
                                
                            case 'sw': // Southwest handle
                                if (selectedShape.type === 'triangle' || selectedShape.type === 'pentagon' || selectedShape.type === 'hexagon') {
                                    const newSize = Math.max(minSize, selectedShape._originalWidth - deltaX);
                                    selectedShape.x = selectedShape._originalX + selectedShape._originalWidth - newSize;
                                    selectedShape.size = newSize;
                                    selectedShape.width = newSize;
                                    selectedShape.height = newSize;
                                } else if (selectedShape.width && selectedShape.height) {
                                    const newWidth = Math.max(minSize, selectedShape._originalWidth - deltaX);
                                    const newHeight = Math.max(minSize, selectedShape._originalHeight + deltaY);
                                    selectedShape.x = selectedShape._originalX + selectedShape._originalWidth - newWidth;
                                    selectedShape.width = newWidth;
                                    selectedShape.height = newHeight;
                                }
                                break;
                                
                            case 'w': // West handle - resize width and move x
                                if (selectedShape.type === 'triangle' || selectedShape.type === 'pentagon' || selectedShape.type === 'hexagon') {
                                    const newSize = Math.max(minSize, selectedShape._originalWidth - deltaX);
                                    selectedShape.x = selectedShape._originalX + selectedShape._originalWidth - newSize;
                                    selectedShape.size = newSize;
                                    selectedShape.width = newSize;
                                    selectedShape.height = newSize;
                                } else if (selectedShape.width) {
                                    const newWidth = Math.max(minSize, selectedShape._originalWidth - deltaX);
                                    selectedShape.x = selectedShape._originalX + selectedShape._originalWidth - newWidth;
                                    selectedShape.width = newWidth;
                                }
                                break;
                                
                            case 'nw': // Northwest handle
                                if (selectedShape.type === 'triangle' || selectedShape.type === 'pentagon' || selectedShape.type === 'hexagon') {
                                    const newSize = Math.max(minSize, selectedShape._originalWidth - deltaX);
                                    selectedShape.x = selectedShape._originalX + selectedShape._originalWidth - newSize;
                                    selectedShape.y = selectedShape._originalY + selectedShape._originalHeight - newSize;
                                    selectedShape.size = newSize;
                                    selectedShape.width = newSize;
                                    selectedShape.height = newSize;
                                } else if (selectedShape.width && selectedShape.height) {
                                    const newWidth = Math.max(minSize, selectedShape._originalWidth - deltaX);
                                    const newHeight = Math.max(minSize, selectedShape._originalHeight - deltaY);
                                    selectedShape.x = selectedShape._originalX + selectedShape._originalWidth - newWidth;
                                    selectedShape.y = selectedShape._originalY + selectedShape._originalHeight - newHeight;
                                    selectedShape.width = newWidth;
                                    selectedShape.height = newHeight;
                                }
                                break;
                                
                            case 'n': // North handle - resize height and move y
                                if (selectedShape.type === 'triangle' || selectedShape.type === 'pentagon' || selectedShape.type === 'hexagon') {
                                    const newSize = Math.max(minSize, selectedShape._originalHeight - deltaY);
                                    selectedShape.y = selectedShape._originalY + selectedShape._originalHeight - newSize;
                                    selectedShape.size = newSize;
                                    selectedShape.width = newSize;
                                    selectedShape.height = newSize;
                                } else if (selectedShape.height) {
                                    const newHeight = Math.max(minSize, selectedShape._originalHeight - deltaY);
                                    selectedShape.y = selectedShape._originalY + selectedShape._originalHeight - newHeight;
                                    selectedShape.height = newHeight;
                                }
                                break;
                                
                            case 'ne': // Northeast handle
                                if (selectedShape.type === 'triangle' || selectedShape.type === 'pentagon' || selectedShape.type === 'hexagon') {
                                    const newSize = Math.max(minSize, selectedShape._originalWidth + deltaX);
                                    selectedShape.y = selectedShape._originalY + selectedShape._originalHeight - newSize;
                                    selectedShape.size = newSize;
                                    selectedShape.width = newSize;
                                    selectedShape.height = newSize;
                                } else if (selectedShape.width && selectedShape.height) {
                                    const newWidth = Math.max(minSize, selectedShape._originalWidth + deltaX);
                                    const newHeight = Math.max(minSize, selectedShape._originalHeight - deltaY);
                                    selectedShape.width = newWidth;
                                    selectedShape.y = selectedShape._originalY + selectedShape._originalHeight - newHeight;
                                    selectedShape.height = newHeight;
                                }
                                break;
                        }
                        
                    } else {
                        // Move existing shape with proper offset
                        selectedShape.x = currentX - dragOffsetX;
                        selectedShape.y = currentY - dragOffsetY;
                        
                        // Keep shape within canvas bounds
                        const shapeWidth = selectedShape.width || selectedShape.size || selectedShape.radius * 2 || 40;
                        const shapeHeight = selectedShape.height || selectedShape.size || selectedShape.radius * 2 || 40;
                        selectedShape.x = Math.max(0, Math.min(selectedShape.x, canvas.width - shapeWidth));
                        selectedShape.y = Math.max(0, Math.min(selectedShape.y, canvas.height - shapeHeight));
                    }
                    
                    redrawCanvas();
                    trackChanges();
                } else if (currentTool === 'area') {
                    // Draw new area
                    redrawCanvas();
                    ctx.strokeStyle = document.getElementById('strokeColor').value;
                    ctx.lineWidth = parseInt(document.getElementById('borderWidth').value);
                    ctx.strokeRect(startX, startY, currentX - startX, currentY - startY);
                }
            });
            
            canvas.addEventListener('mouseup', function() {
                if (isDrawing && currentTool === 'area' && !selectedShape) {
                    const rect = canvas.getBoundingClientRect();
                    const endX = event.clientX - rect.left;
                    const endY = event.clientY - rect.top;
                    
                    const shape = {
                        type: 'rectangle',
                        x: Math.min(startX, endX),
                        y: Math.min(startY, endY),
                        width: Math.abs(endX - startX),
                        height: Math.abs(endY - startY),
                        fillColor: document.getElementById('fillColor').value,
                        strokeColor: document.getElementById('strokeColor').value,
                        borderWidth: parseInt(document.getElementById('borderWidth').value),
                        id: Date.now()
                    };
                    shapes.push(shape);
                    redrawCanvas();
                    hideInstructions();
                    trackChanges();
                }
                
                isDrawing = false;
                isResizing = false;
                resizeHandle = null;
                isRotating = false;
                rotationCenter = null;
                
                // Clear original dimensions after resizing
                if (selectedShape) {
                    delete selectedShape._originalWidth;
                    delete selectedShape._originalHeight;
                    delete selectedShape._originalX;
                    delete selectedShape._originalY;
                    delete selectedShape._originalRotation;
                    delete selectedShape._initialAngle;
                }
                
                // Keep selected shape for showing handles, don't reset to null
                // selectedShape = null; // Commented out so handles stay visible
                
                // Reset cursor
                canvas.style.cursor = 'crosshair';
            });
            
            // Double-click event listener to open properties panel
            canvas.addEventListener('dblclick', function(e) {
                const rect = canvas.getBoundingClientRect();
                const clickX = e.clientX - rect.left;
                const clickY = e.clientY - rect.top;
                
                // Check if double-clicking on a shape
                const clickedShape = getShapeAt(clickX, clickY);
                if (clickedShape) {
                    // Select the shape and show properties panel
                    selectedShape = clickedShape;
                    populateItemPropertiesPanel(selectedShape);
                    itemPropertiesPanel.style.display = 'block';
                    redrawCanvas();
                }
            });
            
            // Get shape at position
            function getShapeAt(x, y) {
                for (let i = shapes.length - 1; i >= 0; i--) {
                    const shape = shapes[i];
                    
                    // Check different shape types
                    if (shape.type === 'rectangle' || shape.type === 'table' || shape.type === 'desk' || 
                        shape.type === 'booth' || shape.type === 'counter' || shape.type === 'stage' || 
                        shape.type === 'screen' || shape.type === 'banner' || shape.type === 'kiosk' ||
                        shape.type === 'entrance' || shape.type === 'exit' || shape.type === 'elevator' ||
                        shape.type === 'stairs' || shape.type === 'restroom' || shape.type === 'security' ||
                        shape.type === 'firstaid' || shape.type === 'fire' || shape.type === 'power' ||
                        shape.type === 'tree' || shape.type === 'road' || shape.type === 'building' ||
                        shape.type === 'parking' || shape.type === 'fountain' || shape.type === 'garden') {
                        if (x >= shape.x && x <= shape.x + (shape.width || 40) &&
                            y >= shape.y && y <= shape.y + (shape.height || 40)) {
                            return shape;
                        }
                    } else if (shape.type === 'circle') {
                        const centerX = shape.x + shape.radius;
                        const centerY = shape.y + shape.radius;
                        const distance = Math.sqrt(Math.pow(x - centerX, 2) + Math.pow(y - centerY, 2));
                        if (distance <= shape.radius) {
                            return shape;
                        }
                    } else if (shape.type === 'chair') {
                        const size = shape.size || 30;
                        if (x >= shape.x && x <= shape.x + size &&
                            y >= shape.y && y <= shape.y + size) {
                            return shape;
                        }
                    } else if (shape.type === 'person') {
                        const width = shape.width || 20;
                        const height = shape.height || 25;
                        if (x >= shape.x && x <= shape.x + width &&
                            y >= shape.y && y <= shape.y + height) {
                            return shape;
                        }
                    } else if (shape.type === 'projector') {
                        const width = shape.width || 30;
                        const height = shape.height || 15;
                        if (x >= shape.x && x <= shape.x + width &&
                            y >= shape.y && y <= shape.y + height) {
                            return shape;
                        }
                    } else if (shape.type === 'triangle' || shape.type === 'pentagon' || shape.type === 'hexagon') {
                        const size = shape.size || 30;
                        if (x >= shape.x && x <= shape.x + size &&
                            y >= shape.y && y <= shape.y + size) {
                            return shape;
                        }
                    } else if (shape.type === 'arrow' || shape.type === 'line') {
                        // Arrow and line detection using width/height
                        const width = shape.width || 80;
                        const height = shape.type === 'line' ? Math.max(shape.height || 2, 8) : shape.height || 20;
                        if (x >= shape.x && x <= shape.x + width &&
                            y >= shape.y && y <= shape.y + height) {
                            return shape;
                        }
                    } else if (shape.type === 'group') {
                        // Group uses width and height from shape properties
                        const width = shape.width || 60;
                        const height = shape.height || 25;
                        if (x >= shape.x && x <= shape.x + width &&
                            y >= shape.y && y <= shape.y + height) {
                            return shape;
                        }
                    } else if (shape.type === 'text') {
                        // Text detection using width and height
                        if (x >= shape.x && x <= shape.x + (shape.width || 120) &&
                            y >= shape.y && y <= shape.y + (shape.height || 30)) {
                            return shape;
                        }
                    }
                }
                return null;
            }
            

            
            // Button event listeners
            document.getElementById('addTextBtn').addEventListener('click', addText);
            
            // Keyboard event listeners
            document.addEventListener('keydown', function(e) {
                // Delete key (Delete or Backspace) - only if not editing text inputs
                if ((e.key === 'Delete' || e.key === 'Backspace') && selectedShape) {
                    // Check if we're editing a text input
                    const activeElement = document.activeElement;
                    const isEditingInput = activeElement && (
                        activeElement.tagName === 'INPUT' || 
                        activeElement.tagName === 'TEXTAREA' || 
                        activeElement.contentEditable === 'true'
                    );
                    
                    if (!isEditingInput) {
                        e.preventDefault(); // Prevent default browser behavior
                        deleteSelectedShape();
                    }
                }
            });
            
            // Function to delete selected shape
            function deleteSelectedShape() {
                if (selectedShape) {
                    // Find and remove the selected shape from the shapes array
                    const index = shapes.findIndex(shape => shape.id === selectedShape.id);
                    if (index > -1) {
                                            shapes.splice(index, 1);
                    selectedShape = null; // Clear selection
                    redrawCanvas(); // Redraw without the deleted shape
                    trackChanges();
                    }
                }
            }
            
            // Shape tool listeners
            document.querySelectorAll('.shape-tool').forEach(function(tool) {
                tool.addEventListener('click', function(e) {
                    e.preventDefault();
                    const shapeType = this.getAttribute('data-shape');
                    addShape(shapeType);
                });
            });
            
            // Furniture tool listeners
            document.querySelectorAll('.furniture-tool').forEach(function(tool) {
                tool.addEventListener('click', function(e) {
                    e.preventDefault();
                    const furnitureType = this.getAttribute('data-furniture');
                    addShape(furnitureType);
                });
            });
            
            // Exhibition tool listeners
            document.querySelectorAll('.exhibition-tool').forEach(function(tool) {
                tool.addEventListener('click', function(e) {
                    e.preventDefault();
                    const itemType = this.getAttribute('data-item');
                    addShape(itemType);
                });
            });
            
            // People & Access tool listeners
            document.querySelectorAll('.people-tool').forEach(function(tool) {
                tool.addEventListener('click', function(e) {
                    e.preventDefault();
                    const itemType = this.getAttribute('data-item');
                    addShape(itemType);
                });
            });
            
            // Utility tool listeners
            document.querySelectorAll('.utility-tool').forEach(function(tool) {
                tool.addEventListener('click', function(e) {
                    e.preventDefault();
                    const itemType = this.getAttribute('data-item');
                    addShape(itemType);
                });
            });
            
            // Outdoor tool listeners
            document.querySelectorAll('.outdoor-tool').forEach(function(tool) {
                tool.addEventListener('click', function(e) {
                    e.preventDefault();
                    const itemType = this.getAttribute('data-item');
                    addShape(itemType);
                });
            });
            
            document.getElementById('clearCanvasBtn').addEventListener('click', function() {
                if (confirm('Are you sure you want to clear the canvas?')) {
                    shapes = [];
                    redrawCanvas();
                    canvasInstructions.style.display = 'block';
                    trackChanges();
                }
            });
            
            // Properties panel toggle
            hidePropertiesBtn.addEventListener('click', function() {
                propertiesPanel.style.display = 'none';
                showPropertiesBtn.style.display = 'block';
                
                // Add a class to the row to handle the collapsed state
                const row = document.querySelector('.row');
                row.classList.add('properties-collapsed');
            });
            
            showPropertiesBtn.addEventListener('click', function() {
                propertiesPanel.style.display = 'block';
                showPropertiesBtn.style.display = 'none';
                
                // Remove the collapsed state class
                const row = document.querySelector('.row');
                row.classList.remove('properties-collapsed');
            });
            
            // Property change listeners
            document.getElementById('bgColor').addEventListener('change', function() {
                document.getElementById('bgColorText').value = this.value;
                redrawCanvas();
                trackChanges();
            });
            
            // Add trackChanges to other property changes
            document.getElementById('fillColor').addEventListener('change', function() {
                redrawCanvas();
                trackChanges();
            });
            
            document.getElementById('strokeColor').addEventListener('change', function() {
                redrawCanvas();
                trackChanges();
            });
            
            document.getElementById('textColor').addEventListener('change', function() {
                redrawCanvas();
                trackChanges();
            });
            
            document.getElementById('borderWidth').addEventListener('change', function() {
                redrawCanvas();
                trackChanges();
            });
            
            document.getElementById('fontFamily').addEventListener('change', function() {
                redrawCanvas();
                trackChanges();
            });
            
            document.getElementById('fontSize').addEventListener('change', function() {
                redrawCanvas();
                trackChanges();
            });
            
            document.getElementById('gridSize').addEventListener('change', function() {
                redrawCanvas();
                trackChanges();
            });
            
            document.getElementById('gridColor').addEventListener('change', function() {
                redrawCanvas();
                trackChanges();
            });
            
            document.getElementById('showGrid').addEventListener('change', function() {
                redrawCanvas();
                trackChanges();
            });
            
            document.getElementById('snapToGrid').addEventListener('change', function() {
                redrawCanvas();
                trackChanges();
            });
            
            document.getElementById('defaultBoothCapacity').addEventListener('change', function() {
                trackChanges();
            });
            
            document.getElementById('labelPrefix').addEventListener('change', function() {
                trackChanges();
            });
            
            document.getElementById('startingLabelNumber').addEventListener('change', function() {
                trackChanges();
            });
            
            document.getElementById('defaultLabelPosition').addEventListener('change', function() {
                trackChanges();
            });
            
            document.getElementById('defaultPrice').addEventListener('change', function() {
                trackChanges();
            });
            
            document.getElementById('enableAutoLabeling').addEventListener('change', function() {
                trackChanges();
            });
            
            document.getElementById('fillColor').addEventListener('change', function() {
                document.getElementById('fillColorText').value = this.value;
            });
            
            document.getElementById('strokeColor').addEventListener('change', function() {
                document.getElementById('strokeColorText').value = this.value;
            });
            
            document.getElementById('gridSize').addEventListener('change', redrawCanvas);
            document.getElementById('showGrid').addEventListener('change', redrawCanvas);
            
            // Booking properties event listeners
            document.getElementById('defaultBoothCapacity').addEventListener('change', function() {
                defaultBoothCapacity = parseInt(this.value);
            });
            
            document.getElementById('labelPrefix').addEventListener('change', function() {
                labelPrefix = this.value;
            });
            
            document.getElementById('startingLabelNumber').addEventListener('change', function() {
                startingLabelNumber = parseInt(this.value);
            });
            
            document.getElementById('enableAutoLabeling').addEventListener('change', function() {
                enableAutoLabeling = this.checked;
            });
            
            document.getElementById('defaultPrice').addEventListener('change', function() {
                defaultPrice = parseFloat(this.value);
            });
            
            // Canvas size change listener
            document.getElementById('canvasSize').addEventListener('change', function() {
                const size = this.value;
                if (size === 'custom') {
                    // Prompt for custom dimensions
                    const width = prompt('Enter canvas width (px):', '800');
                    const height = prompt('Enter canvas height (px):', '600');
                    if (width && height) {
                        resizeCanvas(parseInt(width), parseInt(height));
                    }
                } else {
                    // Parse predefined sizes
                    const [width, height] = size.split('x').map(Number);
                    resizeCanvas(width, height);
                }
            });
            
            // Label position change
            const defaultLabelPositionSelect = document.getElementById('defaultLabelPosition');
            if (defaultLabelPositionSelect) {
                defaultLabelPositionSelect.addEventListener('change', function() {
                    defaultLabelPosition = this.value;
                    redrawCanvas();
                });
            }
            
            // Item properties panel functionality
            const itemPropertiesPanel = document.getElementById('itemPropertiesPanel');
            const closeItemProperties = document.getElementById('closeItemProperties');
            const updateItemProperties = document.getElementById('updateItemProperties');
            const resetItemProperties = document.getElementById('resetItemProperties');
            
            // Close item properties panel
            closeItemProperties.addEventListener('click', function() {
                itemPropertiesPanel.style.display = 'none';
            });
            
            // Update item properties
            updateItemProperties.addEventListener('click', function() {
                if (selectedShape) {
                    selectedShape.itemName = document.getElementById('itemName').value;
                    selectedShape.label = document.getElementById('itemLabel').value;
                    
                    // Handle label position (empty string means use default)
                    const labelPositionValue = document.getElementById('itemLabelPosition').value;
                    if (labelPositionValue === '') {
                        delete selectedShape.labelPosition; // Remove custom position to use default
                    } else {
                        selectedShape.labelPosition = labelPositionValue;
                    }
                    
                    selectedShape.bookable = document.getElementById('itemBookable').checked;
                    selectedShape.maxCapacity = parseInt(document.getElementById('itemMaxCapacity').value) || 1;
                    
                    // Handle price (empty string means use default)
                    const priceValue = document.getElementById('itemPrice').value;
                    if (priceValue === '') {
                        selectedShape.price = null; // Use default price
                    } else {
                        selectedShape.price = parseFloat(priceValue);
                    }
                    
                    redrawCanvas();
                    itemPropertiesPanel.style.display = 'none';
                    trackChanges();
                }
            });
            
            // Reset item properties to defaults
            resetItemProperties.addEventListener('click', function() {
                if (selectedShape) {
                    // Reset to type-specific defaults
                    if (selectedShape.type === 'booth' || selectedShape.type === 'table' || selectedShape.type === 'desk') {
                        selectedShape.bookable = true;
                        selectedShape.maxCapacity = defaultBoothCapacity;
                        if (enableAutoLabeling && !selectedShape.label) {
                            boothCounter++;
                            selectedShape.label = labelPrefix + (startingLabelNumber + boothCounter - 1);
                        }
                    } else {
                        selectedShape.bookable = false;
                        selectedShape.maxCapacity = 1;
                    }
                    
                    // Reset label position and price to use defaults
                    delete selectedShape.labelPosition;
                    selectedShape.price = defaultPrice;
                    
                    populateItemPropertiesPanel(selectedShape);
                    redrawCanvas();
                    trackChanges();
                }
            });
            
            // Function to populate item properties panel
            function populateItemPropertiesPanel(shape) {
                document.getElementById('itemName').value = shape.itemName || '';
                document.getElementById('itemLabel').value = shape.label || '';
                
                // Set label position (empty string if using default)
                const labelPositionSelect = document.getElementById('itemLabelPosition');
                if (shape.labelPosition) {
                    labelPositionSelect.value = shape.labelPosition;
                } else {
                    labelPositionSelect.value = ''; // Use default
                }
                
                document.getElementById('itemBookable').checked = shape.bookable || false;
                document.getElementById('itemMaxCapacity').value = shape.maxCapacity || 1;
                
                // Set price (empty if using default)
                if (shape.price !== null && shape.price !== undefined) {
                    document.getElementById('itemPrice').value = shape.price;
                } else {
                    document.getElementById('itemPrice').value = '';
                }
                
                // Show/hide capacity and price groups based on bookable status
                const capacityGroup = document.getElementById('capacityGroup');
                const priceGroup = document.getElementById('priceGroup');
                capacityGroup.style.display = shape.bookable ? 'block' : 'none';
                priceGroup.style.display = shape.bookable ? 'block' : 'none';
            }
            
            // Function to draw labels on bookable items
            function drawItemLabel(shape) {
                if (shape.label) {
                    let width, height;
                    
                    if (shape.type === 'circle') {
                        width = (shape.radius || 30) * 2;
                        height = (shape.radius || 30) * 2;
                    } else if (shape.type === 'triangle' || shape.type === 'pentagon' || shape.type === 'hexagon') {
                        width = shape.size || 40;
                        height = shape.size || 40;
                    } else if (shape.type === 'arrow' || shape.type === 'line') {
                        width = shape.width || 80;
                        height = shape.type === 'line' ? Math.max(shape.height || 2, 8) : shape.height || 20;
                    } else {
                        width = shape.width || shape.size || 40;
                        height = shape.height || shape.size || 40;
                    }
                    
                    let centerX = shape.type === 'circle' ? (shape.x + (shape.radius || width/2)) : (shape.x + width / 2);
                    let centerY = shape.type === 'circle' ? (shape.y + (shape.radius || height/2)) : (shape.y + height / 2);

                    // Measure text to size background
                    ctx.font = 'bold 10px Arial';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    const textMetrics = ctx.measureText(shape.label);
                    const textW = Math.ceil(textMetrics.width);
                    const padX = 6;
                    const padY = 4;
                    const boxW = Math.max(24, textW + padX * 2);
                    const boxH = 16;

                    // Determine position
                    let boxX = centerX - boxW / 2;
                    let boxY = centerY - boxH / 2;
                    const offset = 6; // small gap from item edge
                    const position = shape.labelPosition || defaultLabelPosition;

                    if (position === 'top') {
                        boxX = centerX - boxW / 2;
                        boxY = shape.y - boxH - offset;
                    } else if (position === 'bottom') {
                        boxX = centerX - boxW / 2;
                        boxY = shape.y + height + offset;
                    } else if (position === 'left') {
                        boxX = shape.x - boxW - offset;
                        boxY = centerY - boxH / 2;
                    } else if (position === 'right') {
                        boxX = shape.x + width + offset;
                        boxY = centerY - boxH / 2;
                    } else if (position === 'center') {
                        boxX = centerX - boxW / 2;
                        boxY = centerY - boxH / 2;
                    }

                    // Round coordinates to prevent blurring
                    boxX = Math.round(boxX);
                    boxY = Math.round(boxY);
                    const textX = Math.round(boxX + boxW / 2);
                    const textY = Math.round(boxY + boxH / 2);

                    // Draw label background and border
                    ctx.fillStyle = 'rgba(0, 123, 255, 0.9)';
                    ctx.fillRect(boxX, boxY, boxW, boxH);
                    ctx.strokeStyle = '#007bff';
                    ctx.lineWidth = 1;
                    ctx.strokeRect(boxX, boxY, boxW, boxH);

                    // Draw text with improved rendering
                    ctx.fillStyle = '#ffffff';
                    ctx.font = 'bold 10px Arial'; // Re-set font for consistency
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(shape.label, textX, textY);

                    // reset alignments for other drawings
                    ctx.textAlign = 'start';
                    ctx.textBaseline = 'alphabetic';
                }
            }
            

            
            // Save floorplan functionality
            document.getElementById('saveFloorplanBtn').addEventListener('click', function() {
                saveFloorplan();
            });
            
            // Top save button functionality
            document.getElementById('saveFloorplanBtnTop').addEventListener('click', function() {
                saveFloorplan();
            });
            
            // Reset defaults button functionality
            document.getElementById('resetDefaults').addEventListener('click', function() {
                if (confirm('Are you sure you want to reset all properties to defaults? This will affect the current floorplan.')) {
                    // Reset all properties to defaults
                    document.getElementById('bgColor').value = '#ffffff';
                    document.getElementById('fillColor').value = '#e5e7eb';
                    document.getElementById('strokeColor').value = '#374151';
                    document.getElementById('textColor').value = '#111827';
                    document.getElementById('borderWidth').value = '2';
                    document.getElementById('fontFamily').value = 'Arial';
                    document.getElementById('fontSize').value = '12';
                    document.getElementById('gridSize').value = '20';
                    document.getElementById('gridColor').value = '#e5e7eb';
                    document.getElementById('showGrid').checked = true;
                    document.getElementById('snapToGrid').checked = true;
                    document.getElementById('defaultBoothCapacity').value = '5';
                    document.getElementById('labelPrefix').value = 'A';
                    document.getElementById('startingLabelNumber').value = '1';
                    document.getElementById('defaultLabelPosition').value = 'top';
                    document.getElementById('defaultPrice').value = '100';
                    document.getElementById('enableAutoLabeling').checked = true;
                    
                    redrawCanvas();
                    trackChanges();
                }
            });
            
            // Save floorplan function
            function saveFloorplan() {
                const button = document.getElementById('saveFloorplanBtn');
                const buttonTop = document.getElementById('saveFloorplanBtnTop');
                const originalText = button.innerHTML;
                const originalTextTop = buttonTop.innerHTML;
                
                // Show loading state on both buttons
                button.innerHTML = '<i class="bi bi-arrow-clockwise me-1 spin"></i><span class="small">Saving...</span>';
                button.disabled = true;
                buttonTop.innerHTML = '<i class="bi bi-arrow-clockwise me-1 spin"></i>Saving...';
                buttonTop.disabled = true;
                
                // Collect floorplan properties
                const floorplanData = {
                    name: document.getElementById('floorplanName').value || 'Main Floorplan',
                    canvas_size: document.getElementById('canvasSize').value,
                    canvas_width: canvas.width,
                    canvas_height: canvas.height,
                    bg_color: document.getElementById('bgColor').value,
                    fill_color: document.getElementById('fillColor').value,
                    stroke_color: document.getElementById('strokeColor').value,
                    text_color: document.getElementById('textColor').value,
                    border_width: parseInt(document.getElementById('borderWidth').value),
                    font_family: document.getElementById('fontFamily').value,
                    font_size: parseInt(document.getElementById('fontSize').value),
                    grid_size: parseInt(document.getElementById('gridSize').value),
                    grid_color: document.getElementById('gridColor').value,
                    show_grid: document.getElementById('showGrid').checked,
                    snap_to_grid: document.getElementById('snapToGrid').checked,
                    default_booth_capacity: parseInt(document.getElementById('defaultBoothCapacity').value),
                    label_prefix: document.getElementById('labelPrefix').value,
                    starting_label_number: parseInt(document.getElementById('startingLabelNumber').value),
                    default_label_position: document.getElementById('defaultLabelPosition').value,
                    default_price: parseFloat(document.getElementById('defaultPrice').value),
                    enable_auto_labeling: document.getElementById('enableAutoLabeling').checked,
                    items: shapes.map(shape => ({
                        item_id: shape.id.toString(),
                        type: shape.type,
                        x: shape.x,
                        y: shape.y,
                        width: shape.width || null,
                        height: shape.height || null,
                        radius: shape.radius || null,
                        size: shape.size || null,
                        rotation: shape.rotation || 0,
                        fill_color: shape.fillColor,
                        stroke_color: shape.strokeColor,
                        border_width: shape.borderWidth,
                        font_family: shape.fontFamily || null,
                        font_size: shape.fontSize || null,
                        text_color: shape.textColor || null,
                        bookable: shape.bookable || false,
                        max_capacity: shape.maxCapacity || 1,
                        label: shape.label || null,
                        item_name: shape.itemName || null,
                        price: shape.price || null,
                        label_position: shape.labelPosition || null,
                        text_content: shape.text || null
                    }))
                };
                
                // Send to backend
                fetch(`{{ route('events.floorplan.save', $event) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(floorplanData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showNotification('success', data.message);
                        
                        // Reset change tracking after successful save
                        hasUnsavedChanges = false;
                        updateSaveButtonVisibility();
                    } else {
                        // Show error message
                        showNotification('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error saving floorplan:', error);
                    showNotification('error', 'An error occurred while saving the floorplan.');
                })
                .finally(() => {
                    // Restore button state on both buttons
                    button.innerHTML = originalText;
                    button.disabled = false;
                    buttonTop.innerHTML = originalTextTop;
                    buttonTop.disabled = false;
                });
            }
            
            // Load floorplan function
            function loadFloorplan() {
                fetch(`{{ route('events.floorplan.load', $event) }}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const floorplan = data.floorplan;
                        
                        // Apply floorplan properties
                        document.getElementById('floorplanName').value = floorplan.name || '';
                        document.getElementById('canvasSize').value = floorplan.canvas_size || '800x600';
                        document.getElementById('bgColor').value = floorplan.bg_color || '#ffffff';
                        document.getElementById('fillColor').value = floorplan.fill_color || '#e5e7eb';
                        document.getElementById('strokeColor').value = floorplan.stroke_color || '#374151';
                        document.getElementById('textColor').value = floorplan.text_color || '#111827';
                        document.getElementById('borderWidth').value = floorplan.border_width || 2;
                        document.getElementById('fontFamily').value = floorplan.font_family || 'Arial';
                        document.getElementById('fontSize').value = floorplan.font_size || 12;
                        document.getElementById('gridSize').value = floorplan.grid_size || 20;
                        document.getElementById('gridColor').value = floorplan.grid_color || '#e5e7eb';
                        document.getElementById('showGrid').checked = floorplan.show_grid !== false;
                        document.getElementById('snapToGrid').checked = floorplan.snap_to_grid !== false;
                        document.getElementById('defaultBoothCapacity').value = floorplan.default_booth_capacity || 5;
                        document.getElementById('labelPrefix').value = floorplan.label_prefix || 'A';
                        document.getElementById('startingLabelNumber').value = floorplan.starting_label_number || 1;
                        document.getElementById('defaultLabelPosition').value = floorplan.default_label_position || 'top';
                        document.getElementById('defaultPrice').value = floorplan.default_price || 100;
                        document.getElementById('enableAutoLabeling').checked = floorplan.enable_auto_labeling !== false;
                        
                        // Resize canvas if needed
                        if (floorplan.canvas_width && floorplan.canvas_height) {
                            resizeCanvas(floorplan.canvas_width, floorplan.canvas_height);
                        }
                        
                        // Load items
                        if (floorplan.items && floorplan.items.length > 0) {
                            shapes = floorplan.items.map(item => ({
                                id: parseInt(item.item_id),
                                type: item.type,
                                x: parseFloat(item.x),
                                y: parseFloat(item.y),
                                width: item.width ? parseFloat(item.width) : undefined,
                                height: item.height ? parseFloat(item.height) : undefined,
                                radius: item.radius ? parseFloat(item.radius) : undefined,
                                size: item.size ? parseFloat(item.size) : undefined,
                                rotation: parseFloat(item.rotation) || 0,
                                fillColor: item.fill_color,
                                strokeColor: item.stroke_color,
                                borderWidth: parseInt(item.border_width),
                                fontFamily: item.font_family,
                                fontSize: item.font_size ? parseInt(item.font_size) : undefined,
                                textColor: item.text_color,
                                bookable: item.bookable || false,
                                maxCapacity: parseInt(item.max_capacity) || 1,
                                label: item.label,
                                itemName: item.item_name,
                                price: item.price ? parseFloat(item.price) : null,
                                labelPosition: item.label_position,
                                text: item.text_content
                            }));
                            
                            redrawCanvas();
                        }
                        
                        showNotification('success', 'Floorplan loaded successfully!');
                        
                        // Reset change tracking after loading
                        hasUnsavedChanges = false;
                        updateSaveButtonVisibility();
                    } else {
                        console.log('No floorplan found for this event');
                    }
                })
                .catch(error => {
                    console.error('Error loading floorplan:', error);
                    showNotification('error', 'An error occurred while loading the floorplan.');
                });
            }
            
            // Notification function
            function showNotification(type, message) {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
                notification.style.position = 'fixed';
                notification.style.top = '20px';
                notification.style.right = '20px';
                notification.style.zIndex = '9999';
                notification.style.minWidth = '300px';
                notification.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                document.body.appendChild(notification);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 5000);
            }
            
            // Load existing floorplan on page load
            @if(isset($floorplanDesign))
                loadFloorplan();
            @endif
            
            // Initialize canvas
            initCanvas();
            
            // Orphaned Bookings Management
            document.getElementById('checkOrphanedBookingsBtn').addEventListener('click', function() {
                checkOrphanedBookings();
            });
            
            document.getElementById('cleanupOrphanedBookingsBtn').addEventListener('click', function() {
                if (confirm('Are you sure you want to clean up orphaned bookings? This action cannot be undone and will permanently delete bookings that reference non-existent floorplan items.')) {
                    cleanupOrphanedBookings();
                }
            });
            
            function checkOrphanedBookings() {
                const button = document.getElementById('checkOrphanedBookingsBtn');
                const originalText = button.innerHTML;
                
                button.disabled = true;
                button.innerHTML = '<i class="bi bi-hourglass-split me-1"></i><span class="small">Checking...</span>';
                
                fetch(`/events/{{ $event->slug }}/floorplan/check-orphaned-bookings`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (data.count > 0) {
                                showNotification('warning', `Found ${data.count} orphaned bookings out of ${data.total_bookings} total bookings! Click "Clean Up" to remove them.`);
                                document.getElementById('cleanupOrphanedBookingsBtn').style.display = 'block';
                                
                                // Log details to console for debugging
                                console.log('Orphaned bookings found:', data.orphaned_bookings);
                                console.log('Debug info:', data.debug_info);
                            } else {
                                showNotification('success', `No orphaned bookings found. All ${data.total_bookings} bookings are properly linked to floorplan items.`);
                                document.getElementById('cleanupOrphanedBookingsBtn').style.display = 'none';
                            }
                        } else {
                            showNotification('error', data.message || 'Unknown error occurred');
                            console.error('Error response:', data);
                        }
                    })
                    .catch(error => {
                        console.error('Error checking orphaned bookings:', error);
                        showNotification('error', 'An error occurred while checking for orphaned bookings. Check the console for details.');
                    })
                    .finally(() => {
                        button.disabled = false;
                        button.innerHTML = originalText;
                    });
            }
            
            function cleanupOrphanedBookings() {
                const button = document.getElementById('cleanupOrphanedBookingsBtn');
                const originalText = button.innerHTML;
                
                button.disabled = true;
                button.innerHTML = '<i class="bi bi-hourglass-split me-1"></i><span class="small">Cleaning...</span>';
                
                fetch(`/events/{{ $event->slug }}/floorplan/cleanup-orphaned-bookings`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('success', data.message);
                            document.getElementById('cleanupOrphanedBookingsBtn').style.display = 'none';
                            
                            // Log cleanup details
                            console.log('Cleanup completed:', data);
                        } else {
                            showNotification('error', data.message || 'Unknown error occurred');
                            console.error('Cleanup error response:', data);
                        }
                    })
                    .catch(error => {
                        console.error('Error cleaning up orphaned bookings:', error);
                        showNotification('error', 'An error occurred while cleaning up orphaned bookings. Check the console for details.');
                    })
                    .finally(() => {
                        button.disabled = false;
                        button.innerHTML = originalText;
                    });
            }
        });
    </script>
</x-event-layout>

