<?php
// Mostrar errores (útil en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener el ID del usuario desde la sesión
$user_id = $_SESSION['user_id'];

// Datos de conexión a la base de datos
$host = "localhost";
$usuario = "TC2005B_601_1";
$contrasena = "pAssWd_194742";
$bd = "R_601_1";

// Crear conexión
$conn = new mysqli($host, $usuario, $contrasena, $bd);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Get user's coins (matching worldmap.php)
$monedas = 0;
$stmt = $conn->prepare("SELECT monedas FROM Usuario WHERE ID_usuario = ?");
if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}

$stmt->bind_param("s", $user_id); 
if (!$stmt->execute()) {
    die("Error al ejecutar la consulta: " . $stmt->error);
}

$stmt->bind_result($monedas);

if (!$stmt->fetch()) {
    die("No se encontró el usuario con ID: $user_id o la columna 'monedas' no existe");
}

$stmt->close();

// Function to get user's owned items DIRECTLY from database
function getUserItems($conn, $user_id) {
    // REMOVEMOS la condición a.estado = 1 para incluir todos los artículos comprados
    $sql = "SELECT a.ID_articulo, a.Img_articulo, a.tipo, a.estado 
            FROM Articulo a 
            INNER JOIN Avatar av ON a.ID_articulo = av.ID_articulo 
            WHERE av.ID_usuario = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }
    
    $stmt->bind_param("s", $user_id);
    if (!$stmt->execute()) {
        die("Error al ejecutar la consulta: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $items = array();
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    $stmt->close();
    
    return $items;
}

// Get user's items
$userItems = getUserItems($conn, $user_id);

// Function to process image path for web display
function processImagePath($dbImagePath) {
    // Extract just the filename from the database path
    if (strpos($dbImagePath, 'items/') !== false) {
        $filename = substr($dbImagePath, strpos($dbImagePath, 'items/'));
        return '../' . $filename;
    }
    
    // Fallback: try to extract just the filename
    $pathParts = explode('/', $dbImagePath);
    $filename = end($pathParts);
    return '../items/' . $filename;
}

// Function to determine item type based on database tipo field
function determineItemType($filename, $dbTipo = null) {
    $filename = strtolower($filename);
    
    // Check database tipo field first (using your enum values)
    if ($dbTipo) {
        // Los valores del ENUM ahora coinciden exactamente con las categorías
        return $dbTipo; // 'hat', 'bag' o 'hand'
    }
    
    // Fallback to filename analysis if tipo is null
    if (strpos($filename, 'hat') !== false || strpos($filename, 'head') !== false) {
        return 'hat';
    } elseif (strpos($filename, 'bag') !== false || strpos($filename, 'purse') !== false) {
        return 'bag';
    } elseif (strpos($filename, 'cup') !== false || strpos($filename, 'hand') !== false) {
        return 'hand';
    }
    
    // Default category
    return 'bags';
}

// Function to generate item name from filename
function generateItemName($filename, $itemId) {
    $baseName = pathinfo($filename, PATHINFO_FILENAME);
    
    // Convert common patterns to readable names
    $nameMap = array(
        'bag1' => 'YSL Bag #1',
        'bag2' => 'Designer Bag #2', 
        'hat1' => 'Classic Hat',
        'hat2' => 'Asian Style Hat',
        'cup' => 'Stanley Cup'
    );
    
    if (isset($nameMap[$baseName])) {
        return $nameMap[$baseName];
    }
    
    // Generate generic name
    return 'Item #' . $itemId;
}

// Organize items by type using database data

// Organizar items por tipo usando datos de la base de datos
$itemsByType = array(
    'hat' => array(),  // Sombreros/gorras
    'bag' => array(),  // Bolsas/mochilas
    'hand' => array()  // Objetos para la mano
);

foreach ($userItems as $item) {
    $itemId = $item['ID_articulo'];
    $dbImagePath = $item['Img_articulo'];
    $dbTipo = $item['tipo'];
    
    // Process image path for web display
    $webImagePath = processImagePath($dbImagePath);
    
    // Determine item type
    $itemType = determineItemType($dbImagePath, $dbTipo);
    
    // Generate item name
    $itemName = generateItemName($dbImagePath, $itemId);
    
    // Create item object for JavaScript
    $itemObj = array(
        'id' => 'item_' . $itemId,
        'name' => $itemName,
        'image' => $webImagePath,
        'owned' => true, // Si está en la tabla Avatar, el usuario lo posee
        'db_id' => $itemId,
        'type' => $itemType
    );
    
    $itemsByType[$itemType][] = $itemObj;
}

// Debug output
echo "<!-- Debug: Usuario: $user_id -->\n";
echo "<!-- Debug: Items encontrados: " . count($userItems) . " -->\n";
foreach ($itemsByType as $type => $items) {
    echo "<!-- Debug: $type items: " . count($items) . " -->\n";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avatar - Educación Matemáticas</title>
    <link rel="stylesheet" href="worldmap.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        /* Reset and Base Styles */
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100vh;
            background: rgb(229, 229, 229);
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
        }

        /* Header Styles - Matching worldmap.php */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0px 70px;
            background-color: white;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            font-family: 'Poppins', sans-serif;
            font-size: 24px; 
            font-weight: bold; 
            color: #333;
            height: 70px;
        }

        h1 {
            font-size: 25px;
            font-weight: bold;
            font-style: normal;
            margin: 0;
        }

        nav {
            display: flex;
            gap: 20px;
            font-size: medium;
            align-items: center;
        }

        nav a {
            text-decoration: none;
            color: black;
            font-size: 20px;
            font-weight: bold;
        }

        .user-icon {
            width: 40px;
            height: 40spx;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-icon img {
            width: 100%;
            height: auto;
            border-radius: 50%; 
            object-fit: cover;
        }

        /* Currency display (matching worldmap.php) */
        .currency {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 215, 0, 0.1);
            padding: 8px 12px;
            border-radius: 50px;
            border: 2px solid #FFD700;
            font-weight: bold;
            color: #333;
        }

        .currency img {
            width: 24px;
            height: 24px;
        }

        .currency span {
            font-size: 16px;
        }

        /* Main Container */
        .main-container {
            display: flex;
            height: calc(100vh - 60px);
            position: relative;
        }

        /* Main Layout */
        .avatar-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem;
            transition: margin-right 0.3s ease;
        }

        .avatar-panel.sidebar-open {
            margin-right: 350px;
        }

        .titulo {
            text-align: center;
            margin-top: 2rem;
            margin-bottom: 2rem;
            width: 100%;
        }

        .titulo h1 {
            font-family: "DM Serif Display", serif;
            font-size: 100px;
            text-align: center;
            border-radius: 100rem;
            color: #333;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            margin: 0;
        }

        /* Content Layout */
        .content-layout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 3rem;
            margin-top: 12rem;
            width: 100%;
            max-width: 1000px;
        }

        /* Controls Column */
        .controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            align-items: flex-start;
        }

        /* Avatar Display */
        .avatar-container {
            position: relative;
        }

        .avatar {
            width: 220px;
            height: 200px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sheep-base {
            /*width: 400%;*/
            height: 360%;
            z-index: 1;
        }

        .avatar-item {
            position: absolute;
            z-index: 2;
        }

        .avatar-item.hat {
            height: 360%;

            /*top: -25px;
            left: 50%;*/
            /*transform: translateX(-50%);*/
            /*width: 150px;*/
        }

        .avatar-item.bag {
            /*bottom: -50px;
            right: -30px;*/
            /*width: 230px;*/
            height: 360%;

        }

        .avatar-item.hand {
            /*bottom: 55px;
            left: -25px;*/
            /*width: 10px;*/
            height: 360%;

        }

        /* Control Buttons */
        .controls {
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            gap: 2.5rem;
            align-items: flex-start;
            margin-right: 15rem;
        }

        .control-btn {
            background-color: #FFD700;
            border: 3px solid #333;
            border-radius: 350px;
            font-size: 22px;
            color: #222222;
            padding: 12px 30px;
            cursor: pointer;
            box-shadow: 0 8px #685801;
            font-family: 'Poppins', sans-serif;
            font-weight: bold;
            width: 160px;
            transition: all 0.1s ease-in-out;
            text-align: center;
            z-index: 5;
        }

        .control-btn:active {
            box-shadow: 0 3px #ccac00;
            transform: translateY(3px);
        }

        .control-btn.active {
            background: #ff6b6b;
            color: white;
            border-color: #ff5252;
            box-shadow: 0 8px #cc5252;
        }

        .control-btn.active:active {
            box-shadow: 0 3px #cc5252;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 60px;
            right: -350px;
            width: 350px;
            height: calc(100vh - 60px);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-left: 1px solid rgba(255, 255, 255, 0.3);
            transition: right 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar.open {
            right: 0;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.4rem;
            color: #333;
            margin: 0;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: #666;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }

        .close-btn:hover {
            background: rgba(0,0,0,0.1);
        }

        /* Items Grid */
        .items-grid {
            padding: 1.5rem;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .item-card {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .item-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            border-color: #FFD700;
        }

        .item-card.selected {
            border-color: #ff6b6b;
            background: rgba(255, 107, 107, 0.1);
        }

        .item-image {
            width: 60px;
            height: 60px;
            margin: 0 auto 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .item-image img {
            max-width: 100%;
            max-height: 100%;
        }

        .item-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.3rem;
            font-size: 14px;
        }

        .item-status {
            font-size: 12px;
            color: #666;
        }

        .error-message {
            background: #ffebee;
            color: #c62828;
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem;
            border-left: 4px solid #c62828;
        }

        /* Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.3);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
        }

        .overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Responsive */
        @media (max-width: 768px) {
            header {
                padding: 0px 15px;
            }
            
            .sidebar {
                width: 100%;
                right: -100%;
            }
            
            .avatar-panel.sidebar-open {
                margin-right: 0;
            }
            
            .items-grid {
                grid-template-columns: 1fr;
            }
            
            .titulo h1 {
                font-size: 40px;
            }

            .content-layout {
                flex-direction: column;
                gap: 3rem;
            }

            .controls {
                align-items: center;
                margin-right: 0;
            }

            .control-btn {
                width: 120px;
                font-size: 16px;
            }

            .avatar {
                width: 250px;
                height: 250px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>π sheep</h1>
        <nav>
            <a href="../worldmap.php">home</a>
            <a href="../arena/arena.html">arena</a>
            <a href="avatar.php">avatar</a>
            <a href="../tienda/tienda.php">shop</a>
            <div class="user-icon"><img src="../imgWEB/user.svg" alt="User icon"></div>
        </nav>
    </header>

    <div class="main-container">
        <!-- Avatar Panel -->
        <div class="avatar-panel" id="avatarPanel">
            <div class="titulo">
                <h1>Avatar</h1>
            </div>

            <div class="content-layout">
                <!-- Controls Column -->
                <div class="controls">
                    <button class="control-btn" data-category="head">Hats</button>
                    <button class="control-btn" data-category="bags">Bags</button>
                    <button class="control-btn" data-category="hand">Hand</button>
                </div>

                <!-- Avatar Display -->
                <div class="avatar-container">
                    <div class="avatar">
                        <!-- Base sheep -->
                        <img src="sheep_base.svg" alt="sheep" class="sheep-base">
                        
                        <!-- Avatar items (initially hidden) -->
                        <img id="currentHat" class="avatar-item hat" style="display: none;">
                        <img id="currentBag" class="avatar-item bag" style="display: none;">
                        <img id="currentHand" class="avatar-item hand" style="display: none;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2 class="sidebar-title" id="sidebarTitle">Items</h2>
                <button class="close-btn" id="closeSidebar">✕</button>
            </div>
            <div class="items-grid" id="itemsGrid">
                <!-- Items will be populated by JavaScript -->
            </div>
        </div>

        <!-- Overlay -->
        <div class="overlay" id="overlay"></div>
    </div>

    <script>
        // Get user's items from PHP
        const availableItems = <?php echo json_encode($itemsByType); ?>;
        
        // Add debug logging
        console.log('User items loaded:', availableItems);
        console.log('Current user ID: <?php echo $user_id; ?>');

        // Current equipped items
        let currentItems = {
            hat: null,
            bag: null,
            hand: null
        };

        // DOM Elements
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const avatarPanel = document.getElementById('avatarPanel');
        const sidebarTitle = document.getElementById('sidebarTitle');
        const itemsGrid = document.getElementById('itemsGrid');
        const closeSidebar = document.getElementById('closeSidebar');
        const controlBtns = document.querySelectorAll('.control-btn');

        // Event Listeners
        controlBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const category = btn.getAttribute('data-category');
                openSidebar(category);
                
                // Update active button
                controlBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });

        closeSidebar.addEventListener('click', closeSidebarPanel);
        overlay.addEventListener('click', closeSidebarPanel);

        // Functions
        function openSidebar(category) {
            sidebar.classList.add('open');
            overlay.classList.add('active');
            avatarPanel.classList.add('sidebar-open');
            
            // Update sidebar title
            // Títulos de la sidebar
            const titles = {
                hat: 'Hats',
                bag: 'Bags',
                hand: 'Hand Items'
            };
            sidebarTitle.textContent = titles[category] || 'Items';
            
            // Populate items
            populateItems(category);
        }

        function closeSidebarPanel() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            avatarPanel.classList.remove('sidebar-open');
            
            // Remove active state from buttons
            controlBtns.forEach(b => b.classList.remove('active'));
        }

        function populateItems(category) {
            const items = availableItems[category] || [];
            itemsGrid.innerHTML = '';

            // Add "None" option
            const noneCard = createItemCard({
                id: 'none',
                name: 'None',
                image: '',
                owned: true
            }, category, true);
            itemsGrid.appendChild(noneCard);

            // Add items - only show owned items
            items.forEach(item => {
                if (item.owned) {
                    const itemCard = createItemCard(item, category);
                    itemsGrid.appendChild(itemCard);
                }
            });
            
            // Show message if no items available
            if (items.length === 0) {
                const noItemsMsg = document.createElement('div');
                noItemsMsg.className = 'no-items-message';
                noItemsMsg.style.cssText = 'grid-column: span 2; text-align: center; color: #666; padding: 2rem;';
                noItemsMsg.innerHTML = '<p>No items available in this category.</p><p>Visit the shop to purchase items!</p>';
                itemsGrid.appendChild(noItemsMsg);
            }
        }

        function createItemCard(item, category, isNone = false) {
            const card = document.createElement('div');
            card.className = 'item-card';
            
            // Check if this item is currently selected
            if ((isNone && !currentItems[category]) || 
                (!isNone && currentItems[category] === item.id)) {
                card.classList.add('selected');
            }

            card.innerHTML = `
                <div class="item-image">
                    ${!isNone && item.image ? `<img src="${item.image}" alt="${item.name}" onerror="this.onerror=null; this.src='../items/placeholder.svg'; console.log('Failed to load: ${item.image}');">` : '🚫'}
                </div>
                <div class="item-name">${item.name}</div>
                <div class="item-status">${item.owned ? 'Owned' : 'Not Owned'}</div>
            `;

            card.addEventListener('click', () => {
                if (item.owned || isNone) {
                    selectItem(item.id, category, item.image, isNone);
                    
                    // Update selection in UI
                    document.querySelectorAll('.item-card').forEach(c => c.classList.remove('selected'));
                    card.classList.add('selected');
                }
            });

            return card;
        }

        function selectItem(itemId, category, imageSrc, isNone = false) {
            // Update current items
            currentItems[category] = isNone ? null : itemId;
            
            // Update avatar display
            const avatarElements = {
                head: document.getElementById('currentHat'),
                bags: document.getElementById('currentBag'),
                hand: document.getElementById('currentHand')
            };

            const element = avatarElements[category];
            if (element) {
                if (isNone || !imageSrc) {
                    element.style.display = 'none';
                } else {
                    element.src = imageSrc;
                    element.style.display = 'block';
                    
                    // Handle image load errors with better logging
                    element.onerror = function() {
                        console.error('Failed to load avatar image:', imageSrc);
                        console.log('Trying fallback image path...');
                        
                        // Try different path combinations
                        const fallbackPaths = [
                            imageSrc.replace('../items/', './items/'),
                            imageSrc.replace('../items/', 'items/'),
                            '../items/placeholder.svg'
                        ];
                        
                        this.onerror = null; // Prevent infinite loop
                        this.src = fallbackPaths[0];
                    };
                }
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Avatar system initialized');
            console.log('Available items by category:', availableItems);
        });
    </script>
</body>
</html>