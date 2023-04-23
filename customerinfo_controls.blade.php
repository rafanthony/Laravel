<script>
    
    function CreateContent(){

        var go_Shell = new sap.m.Shell({});
        //left page
        go_App_Left = new sap.m.App({});
        go_App_Left.addPage(create_page_menu());

        //right page
        go_App_Right = new sap.m.App({});
        go_App_Right.addPage(createCustomerPage());	
		go_App_Right.addPage(createDisplayCustPage());
		go_App_Right.addPage(createListCust());
		go_App_Right.addPage(createTestPage());

        go_SplitContainer = new sap.ui.unified.SplitContainer({ content: [go_App_Right], secondaryContent: [go_App_Left]});		
        go_SplitContainer.setSecondaryContentWidth("250px");
        go_SplitContainer.setShowSecondaryContent(true);
        

        let go_App = new sap.m.App({
            pages : [go_SplitContainer]
        });

        go_Shell.setApp(go_App);		
        go_Shell.setAppWidthLimited(false);
        go_Shell.placeAt("content");     
    }

    function create_page_menu(){
        let page = new sap.m.Page({}).addStyleClass('sapUiSizeCompact');
        let pageHeader  = new sap.m.Bar({enableFlexBox: false,contentMiddle:[ new sap.m.Label({text:"Action"})]});
        const menuList = new sap.m.List("MENU_LIST",{});
		const menuListTemplate = new sap.m.StandardListItem("LEFT_MENU_TEMPLATE",{
			title:"{title}",
			icon:"{icon}",
			visible:"{visible}",
			type: sap.m.ListType.Active,
			press:function(oEvent){
				
                let menu = oEvent.getSource().getBindingContext().getProperty('funct');
				let list_items = oEvent.getSource().getParent().getItems();

                for (var i = 0; i < list_items.length; i++) {
                    list_items[i].removeStyleClass('class_selected_list_item');
                   //$('LEFT_MENU_TEMPLATE-MENU_LIST-0').removeClass('class_selected_list_item');
                }
                oEvent.getSource().addStyleClass('class_selected_list_item');
				
				switch(menu){
					case "CREATE_CUS_INFO" :
						screenMode._create();
						go_App_Right.to('CREATE_CUS_PAGE');
					break;
					case "DISPLAY_CUS_INFO" :
						go_App_Right.to('CUS_PAGE_DISPLAY');
					break;
					case "CUS_LIST" :
						listingBp._getData(cusData);
						go_App_Right.to('PAGE_CUS_LISTING');
					break;
					case "CUS_TEST" :

						go_App_Right.to('TEST_PAGE');
					break;
				}
                
			}
		});
		
        const gt_list = [
                {
                    title   : "Create Customer Information",	
					funct  	: "CREATE_CUS_INFO",
                    icon    : "sap-icon://create-form",
                    visible : true
                },
                {
                    title   : "Display Customer Information",
                    icon    : "sap-icon://business-card",
					funct  	: "DISPLAY_CUS_INFO",
                    visible : true
                },
                {
                    title   : "Customer Info Listing",
                    icon    : "sap-icon://list",
					funct  	: "CUS_LIST",
                    visible : true
				}
				//, {
                //     title   : "Test",
                //     icon    : "sap-icon://checklist-item",
				// 	funct  	: "CUS_TEST",
                //     visible : true
                // }

        ];

        let model = new sap.ui.model.json.JSONModel();
			model.setSizeLimit(gt_list.length);
			model.setData(gt_list);

			ui('MENU_LIST').setModel(model).bindAggregation("items",{
				path:"/",
				template:ui('LEFT_MENU_TEMPLATE')
			});
		
        page.setCustomHeader(pageHeader);
		page.addContent(menuList);		
		return page;
    }
            
    function createCustomerPage(){
        let page  = new sap.m.Page("CREATE_CUS_PAGE",{}).addStyleClass('sapUiSizeCompact');
        let pageHeader = new sap.m.Bar("",{  
			enableFlexBox: false,
			contentLeft:[
				new sap.m.Button({ icon:"sap-icon://nav-back",
					press:function(oEvt){
						go_App_Right.back();
					} 
				}),
				new sap.m.Button({icon:"sap-icon://menu2",
					press:function(){
						go_SplitContainer.setSecondaryContentWidth("250px");
						if(!go_SplitContainer.getShowSecondaryContent()){
							go_SplitContainer.setShowSecondaryContent(true);
						} else {							
							go_SplitContainer.setShowSecondaryContent(false);
						}
					
					}
				}), 
				
			],
			contentMiddle:[
                new sap.m.Label("CUS_TITLE",{text:"Create Customer Information"})
            ],
		
		});
        let crumbs = new sap.m.Breadcrumbs("CREATE_CUS_BRDCRMS",{
            currentLocationText: "Create Customer Info",
            links: [
                new sap.m.Link({
                    text:"Home",
                    press:function(oEvt){
                    //    fn_click_breadcrumbs("HOME");
                    }
                }),
				new sap.m.Link("CREATE_CUS_BRDCRMS_TITLE",{
                    text:"Customer Information Management",
                    press:function(oEvt){
                    //    fn_click_breadcrumbs("HOME");
                    }
                }),
				
            ]
        });

		let errorPanel = new sap.m.Panel("MESSAGE_STRIP_CUS_ERROR",{visible:false});


        let createPageFormHeader = new sap.uxap.ObjectPageLayout({
            headerTitle:[
                new sap.uxap.ObjectPageHeader("OBJECTHEADER_CUS_NAME",{
                    objectTitle:"",
					showPlaceholder : false,
					actions:[
                        new sap.uxap.ObjectPageHeaderActionButton("CREATE_CUS_SAVE_BTN1",{
                            icon: "sap-icon://save",
							press: function(evt){
								createCus();

                            }
                        }).addStyleClass("sapMTB-Transparent-CTX"),
                        new sap.uxap.ObjectPageHeaderActionButton("CREATE_CUS_EDIT_BTN1",{
                            icon: "sap-icon://edit",
							press: function(){
									ui("COMPCODE_SAVE_DIALOG").open();
                            }
                        }).addStyleClass("sapMTB-Transparent-CTX"),

                    ],
                })
            ]     
        });

		

		let createPageFormContent = new sap.m.Panel("CUS_GENERAL_PANEL",{
			headerToolbar: [
				new sap.m.Toolbar({
                    content: [
                        new sap.m.ToolbarSpacer(),
                        new sap.m.Button("CREATE_CUS_SAVE_BTN", {
                            visible: true,
                            icon: "sap-icon://save",
                            press: function () {


								ui('CUS_ID').setValueState("None").setValueStateText("");
								ui('MESSAGE_STRIP_CUS_ERROR').destroyContent().setVisible(false);
								let bpId = ui('CUS_ID').getValue().trim();
								let message = "";
								let lv_message_strip = "";
									if(bpId){
										if(screenMode._mode == "create"){
											let isExist = bpDataOrganizer._validateBP(bpId);
											if(isExist){
												message = "Customer Information ID already existed";
												ui('CUS_ID').setValueState("Error").setValueStateText(message);
												lv_message_strip = fn_show_message_strip("MESSAGE_STRIP_CUS_ERROR",message);
												ui('MESSAGE_STRIP_CUS_ERROR').setVisible(true).addContent(lv_message_strip);
											}else{
												ui('CUS_SAVE_DIALOG').open();
											}
										}else{
											createCus();
											ui('CUS_SAVE_DIALOG').open();
										}
										
									}else{
										message = "Customer ID is mandatory";
										ui('CUS_ID').setValueState("Error").setValueStateText(message);
										lv_message_strip = fn_show_message_strip("MESSAGE_STRIP_CUS_ERROR",message);
										ui('MESSAGE_STRIP_CUS_ERROR').setVisible(true).addContent(lv_message_strip);
									}


















								// if(screenMode._mode == "create"){
								// 	createCus();
								// }else{
								// 	bpDataOrganizer._updateById(screenMode._id);
								// }
                            }
                        }),
						new sap.m.Button("CREATE_CUS_EDIT_BTN", {
                            visible: true,
                            icon: "sap-icon://edit",
                            press: function () {
								screenMode._edit();
                            }
                        }),
						new sap.m.Button("CREATE_CUS_CANCEL_BTN", {
                            visible: true,
                            icon: "sap-icon://decline",
                            press: function () {
								screenMode._display(screenMode._id);
                            }
                        }),
                    ]
                }).addStyleClass('class_transparent_bar'),

			],
			content: [
                new sap.ui.layout.Grid({
                    defaultSpan:"L12 M12 S12",
					width:"auto",
					content:[
                        new sap.ui.layout.form.SimpleForm("PANEL_FORM",{
							title: "New Customer Information",
                            maxContainerCols:2,
							labelMinWidth:130,
							content:[

                                new sap.ui.core.Title("GENERAL_INFO_TITLE1",{text:""}),


                                new sap.m.Label({text:"Customer Type",width:"160px"}).addStyleClass('class_label_padding'),
								new sap.m.Select("CUS_TYPE_INFO",{
									width:TextWidth,
									//selectedKey: "",
									items: [
										new sap.ui.core.ListItem({
											text: "New",
											key: "New",
											additionalText: "New",
											icon: "sap-icon://add-employee"
										}),
										new sap.ui.core.ListItem({
											text: "Existing",
											key: "Existing",
											additionalText: "Existing",
											icon: "sap-icon://activity-individual"
										}),
										new sap.ui.core.ListItem({
											text: "Loyal",
											key: "Loyal",
											additionalText: "Loyal",
											icon: "sap-icon://address-book"
										}),
										new sap.ui.core.ListItem({
											text: "Occasional",
											key: "Occasional",
											additionalText: "Occasional",
											icon: "sap-icon://customer-and-contacts"
										})
									]
								}),
								new sap.m.Label({text:"Customer Info ID",width:"150px"}).addStyleClass('class_label_padding'),
								new sap.m.Input("CUS_ID",{
									value:"",
									width:TextWidth,
									liveChange: function(oEvt){
										fn_livechange_numeric_input(oEvt);
									},	
									change : function(oEvt){
										let lv_value = oEvt.getSource().getValue().trim();
										let label = "New Customer Information"
										let lv_bpid = label + " (" + lv_value + ")";
										ui("PANEL_FORM").setTitle(lv_bpid);
										
									}
								}),
								
								
                                new sap.m.Label({text:"First Name",width:labelWidth}).addStyleClass('class_label_padding'),
								new sap.m.Input("CUS_FNAME",{value:"", width:TextWidth}),

								new sap.m.Label({text:"Last Name",width:labelWidth}).addStyleClass('class_label_padding'),
								new sap.m.Input("CUS_LNAME",{value:"", width:TextWidth}),

								new sap.m.Label({text:"Phone Number",width:labelWidth}).addStyleClass('class_label_padding'),
								new sap.m.Input("CUS_PNUM",{
									
									value:"",
									liveChange: function(oEvt){
										fn_livechange_numeric_input(oEvt);
									},
									 width:TextWidth}),
								

                                new sap.m.Label({text:"Gender",width:labelWidth}).addStyleClass('class_label_padding'),
								new sap.m.Input("CUS_GENDER",{value:"", width:TextWidth}),


								new sap.m.Label({text:"Occupation",width:"160px"}).addStyleClass('class_label_padding'),
								new sap.m.Select("CUS_OCCUP",{
									width:TextWidth,
									//selectedKey: "",
									items: [
										new sap.ui.core.ListItem({
											text: "Architect",
											key: "Architect",
											additionalText: "Architect",
											icon: "sap-icon://feeder-arrow"
										}),
										new sap.ui.core.ListItem({
											text: "Engineer",
											key: "Engineer",
											additionalText: "Engineer",
											icon: "sap-icon://feeder-arrow"
										}),
										new sap.ui.core.ListItem({
											text: "Lawyer",
											key: "Lawyer",
											additionalText: "Lawyer",
											icon: "sap-icon://feeder-arrow"
										}),
										new sap.ui.core.ListItem({
											text: "Web Developer",
											key: "Web Developer",
											additionalText: "Web Developer",
											icon: "sap-icon://feeder-arrow"
										}),
									]
								}),
								
                            	
                                new sap.ui.core.Title("GENERAL_INFO_TITLE2",{text:""}),
                              
								
								new sap.m.Label({text:"Date of Birth",width:labelWidth}).addStyleClass('class_label_padding'),
								new sap.m.DateTimePicker("CUS_DOB",{
									liveChange: function(oEvt){
										fn_livechange_numeric_input(oEvt);
									},
									width:TextWidth}),
								
								new sap.m.Label({text:"Email Address",width:labelWidth}).addStyleClass('class_label_padding'),
								new sap.m.Input("EMAIL_ADD",{width:TextWidth}),
								
								new sap.m.Label({text:"Billing Address",width:labelWidth}).addStyleClass('class_label_padding'),
								new sap.m.Input("CUS_BILL",{width:TextWidth}),

								new sap.m.Label({text:"Purchase History",width:labelWidth}).addStyleClass('class_label_padding'),
								new sap.m.Input("CUS_HIS",{width:TextWidth}),

								new sap.m.Label({text:"Payment Method",width:"160px"}).addStyleClass('class_label_padding'),
								new sap.m.Select("CUS_PAYMETHOD",{
									width:TextWidth,
									//selectedKey: "",
									items: [
										new sap.ui.core.ListItem({
											text: "Cash",
											key: "Cash",
											additionalText: "Cash",
											icon: "sap-icon://money-bills"
										}),
										new sap.ui.core.ListItem({
											text: "Credit Card",
											key: "Credit Card",
											additionalText: "Credit Card",
											icon: "sap-icon://business-card"
										})
									]
								}),

								new sap.m.Label({text:"Order Number",width:labelWidth}).addStyleClass('class_label_padding'),
								new sap.m.Input("CUS_ORDER",{
									liveChange: function(oEvt){
										fn_livechange_numeric_input(oEvt);
									},
									width:TextWidth}),

								new sap.m.Label({text:"Company Name",width:labelWidth}).addStyleClass('class_label_padding'),
								new sap.m.Input("CUS_COMPNAME",{width:TextWidth}),
									
                                new sap.m.Label({text:"Deletion Flag",width:labelWidth}).addStyleClass('class_label_padding'),
								new sap.m.Switch("CONTROL_INFO_DEL_FLAG",{state:false}),
                            ]
                        })
                    ]
                })
            ]
        });

        page.setCustomHeader(pageHeader);
        page.addContent(crumbs);
        //page.addContent(createPageFormHeader);
		page.addContent(createPageFormContent);
        return page;
    }

	function createDisplayCustPage(){
				
		var lv_Page  = new sap.m.Page("CUS_PAGE_DISPLAY",{}).addStyleClass('sapUiSizeCompact');
		
		var lv_header = new sap.m.Bar({
			enableFlexBox: false,
			contentLeft:[
				new sap.m.Button({ icon:"sap-icon://nav-back",
					press:function(oEvt){
						go_App_Right.back();
					} 
				}),
				new sap.m.Button({icon:"sap-icon://menu2",
					press:function(){
						go_SplitContainer.setSecondaryContentWidth("250px");
						if(!go_SplitContainer.getShowSecondaryContent()){
							go_SplitContainer.setShowSecondaryContent(true);
						} else {							
							go_SplitContainer.setShowSecondaryContent(false);
						}
					}
				})
				//new sap.m.Image({src: logo_path}),
			],

			contentMiddle:[gv_Lbl_NewPrdPage_Title = new sap.m.Label("DISP_CUS_TITLE",{text:"Display Customer Information"})],
			
			contentRight:[
				new sap.m.Button({
					icon: "sap-icon://home",
					press: function(){
						//pero kani akoang gamit para mabalik sya didto main page
						go_App_Right.to('CREATE_CUS_PAGE');

						// mao ni ang tinuod nga code ni sir
						// window.location.href = MainPageLink; 
					}
				})
			]
		});
		
		var lv_crumbs = new sap.m.Breadcrumbs("DISP_CUS_BRDCRMS",{
            currentLocationText: "Display Customer Info",
            links: [
                new sap.m.Link({
                    text:"Home",
                    press:function(oEvt){
						
                       // fn_click_breadcrumbs("HOME");
                    }
                }),
				new sap.m.Link("DISP_CUS_BRDCRMS_TITLE",{
                    text:"Customer Info Management",
                    press:function(oEvt){
                      //  fn_click_breadcrumbs("HOME");
                    }
                }),
				
            ]
        }).addStyleClass('breadcrumbs-padding');
		
		
		var lv_searchfield =  new sap.m.Bar({
			enableFlexBox: false,
			contentLeft: [
				// actual search field
				new sap.m.SearchField("SEARCHFIELD_DISPLAY_OUTLET",{
					width: "99%",
					liveChange: function(oEvt){
						var lv_search_val = oEvt.getSource().getValue().trim();
						if(lv_search_val == ""){
							ui("DISPLAY_CUS_TABLE").setVisible(false);
						}
					},
					placeholder: "Search...",
					search: function(oEvent){
						var lv_searchval = oEvent.getSource().getValue().trim();
						displayBp._get_data(lv_searchval);
					},
				})
			],
		});
		
		var lv_table = new sap.ui.table.Table("DISPLAY_CUS_TABLE", {
			visible:false,
			visibleRowCountMode:"Auto",
			selectionMode:"None",
			enableCellFilter: true,
			enableColumnReordering:true,
			toolbar:[
				new sap.m.Toolbar({
					design:"Transparent",
					content:[
						new sap.m.Text("DISPLAY_CUS_TABLE_LABEL",{text:"List (0)"}),
					]
				})
			],
			filter:function(oEvt){
				oEvt.getSource().getBinding("rows").attachChange(function(oEvt){
					var lv_row_count = oEvt.getSource().iLength;
					ui('DISPLAY_CUS_TABLE_LABEL').setText("List (" + lv_row_count + ")");
				});
			},
			cellClick: function(oEvt){
				
				var lv_bind = oEvt.getParameters().rowBindingContext;
				
				if(lv_bind != undefined){
					var lv_ci_id = oEvt.getParameters().rowBindingContext.getProperty("CUSTOMER_ID");
					if(lv_ci_id){
						screenMode._display(lv_ci_id);
					}
				}
				
			},
			columns: [
			
				new sap.ui.table.Column({label:new sap.m.Text({text:"Customer Info ID"}),
					width:"20%",
					sortProperty:"CUSTOMER_ID",
					filterProperty:"CUSTOMER_ID",
					autoResizable:true,
					template:new sap.m.Text({text:"{CUSTOMER_ID}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Customer First Name"}),
					width:"20%",
					sortProperty:"FNAME",
					filterProperty:"FNAME",
					autoResizable:true,
					template:new sap.m.Text({text:"{FNAME}",tooltip:"{FNAME}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Customer Last Name"}),
					width:"20%",
					sortProperty:"LNAME",
					filterProperty:"LNAME",
					autoResizable:true,
					template:new sap.m.Text({text:"{LNAME}",tooltip:"{LNAME}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Customer Phone Number"}),
					width:"20%",
					sortProperty:"PHONENUM",
					filterProperty:"PHONENUM",
					autoResizable:true,
					template:new sap.m.Text({text:"{PHONENUM}",tooltip:"{PHONENUM}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Customer Email"}),
					width:"20%",
					sortProperty:"EMAIL",
					filterProperty:"EMAIL",
					autoResizable:true,
					template:new sap.m.Text({text:"{EMAIL}",tooltip:"{EMAIL}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Customer Gender"}),
					width:"20%",
					sortProperty:"GENDER",
					filterProperty:"GENDER",	
					autoResizable:true,
					template:new sap.m.Text({text:"{GENDER}",tooltip:"{GENDER}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Customer Occupation"}),
					width:"20%",
					sortProperty:"OCCUPATION",
					filterProperty:"OCCUPATION",	
					autoResizable:true,
					template:new sap.m.Text({text:"{OCCUPATION}",tooltip:"{OCCUPATION}",maxLines:1}),
				}),	
				new sap.ui.table.Column({label:new sap.m.Text({text:"Customer Date of Birth"}),
					width:"20%",
					sortProperty:"DATE_BIRTH",
					filterProperty:"DATE_BIRTH",	
					autoResizable:true,
					template:new sap.m.Text({text:"{DATE_BIRTH}",tooltip:"{DATE_BIRTH}",maxLines:1}),
				}),			
			]
		});
		
		lv_Page.setCustomHeader(lv_header);
		lv_Page.addContent(lv_crumbs);
		lv_Page.addContent(lv_searchfield);
		lv_Page.addContent(lv_table);
		
		return lv_Page;
	}
	function createListCust(){
		var lv_Page  = new sap.m.Page("PAGE_CUS_LISTING",{}).addStyleClass('sapUiSizeCompact');
		var lv_header = new sap.m.Bar({
			enableFlexBox: false,
			contentLeft:[
				new sap.m.Button({ icon:"sap-icon://nav-back",
					press:function(oEvt){ 
						
							go_App_Right.back();
						
					}
				}),
				new sap.m.Button({icon:"sap-icon://menu2",
					press:function(){
						go_SplitContainer.setSecondaryContentWidth("270px");
						if(!go_SplitContainer.getShowSecondaryContent()){
							go_SplitContainer.setShowSecondaryContent(true);
						} else {							
							go_SplitContainer.setShowSecondaryContent(false);
						}
					}
				}), 
				//new sap.m.Image({src: logo_path}),
				],
			contentMiddle:[gv_Lbl_NewPrdPage_Title = new sap.m.Label("CUS_LISTING_PAGE_LABEL",{text:"Customer Info Listing"})],
			
			contentRight:[
				//fn_help_button(SelectedAppID,"BP_LISTING"),
				new sap.m.Button({  
					icon: "sap-icon://home",
					press: function(){
						//pero kani akoang gamit para mabalik sya didto main page
						go_App_Right.to('CREATE_CUS_PAGE');

						// mao ni ang tinuod nga code ni sir
					// window.location.href = MainPageLink; 
					}
				})
			]
		});
					
		var lv_crumbs = new sap.m.Breadcrumbs("LIST_CUS_BRDCRMS",{
			currentLocationText: "Customer Info Listing",
			links: [
				new sap.m.Link({
					text:"Home",
					press:function(oEvt){
					// fn_click_breadcrumbs("HOME");
					}
				}),
				new sap.m.Link("LIST_CUS_BRDCRMS_TITLE",{
					text:"Customer Info Management",
					press:function(oEvt){
					//  fn_click_breadcrumbs("HOME");
					}
				}),
				
			]
		}).addStyleClass('breadcrumbs-padding');


		var lv_table = new sap.ui.table.Table("CUS_LISTING_TABLE",{
			visibleRowCountMode:"Auto",
			selectionMode:"None",
			enableCellFilter: true,
			enableColumnReordering:true,
			filter:function(oEvt){
				oEvt.getSource().getBinding("rows").attachChange(function(oEvt){
					var lv_row_count = oEvt.getSource().iLength;
					ui('CUS_LISTING_LABEL').setText("Customer Information (" + lv_row_count + ")");
				});
			},
			toolbar: [
                new sap.m.Toolbar({
                    content: [
                        new sap.m.Label("CUS_LISTING_LABEL", {
                            text:"Customer Info (0)"
                        }),
                        new sap.m.ToolbarSpacer(),
                        new sap.m.Button("BTN_DOWNLOAD", {
                            visible: true,
                            icon: "sap-icon://download",
                            press: function () {
								
                            }
                        })
                    ]
                }).addStyleClass('class_transparent_bar'),
            ],
			cellClick: function(oEvt){
				
				var lv_bind = oEvt.getParameters().rowBindingContext;
				if(lv_bind != undefined){
					var lv_ci_id = oEvt.getParameters().rowBindingContext.getProperty("CUSTOMER_ID");
					if(lv_ci_id){
						screenMode._display(lv_ci_id);
					}
				}
			},
			columns:[
				
				new sap.ui.table.Column({label:new sap.m.Text({text:"Customer Info ID"}),
					width:"150px",
					sortProperty:"CUSTOMER_ID",
					filterProperty:"CUSTOMER_ID",
					//autoResizable:true,
					template:new sap.m.Text({text:"{CUSTOMER_ID}",tooltip:"{CUSTOMER_ID}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Customer Type"}),
					width:"150px",
					sortProperty:"TYPE",
					filterProperty:"TYPE",
					autoResizable:true,
					template:new sap.m.Text({text:"{TYPE}",tooltip:"{TYPE}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"First Name"}),
					width:"150px",
					sortProperty:"FNAME",
					filterProperty:"FNAME",
					template:new sap.m.Text({text:"{FNAME}",tooltip:"{FNAME}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Last Name"}),
					width:"150px",
					sortProperty:"LNAME",
					filterProperty:"LNAME",
					template:new sap.m.Text({text:"{LNAME}",tooltip:"{LNAME}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Email"}),
					width:"150px",
					sortProperty:"EMAIL",
					filterProperty:"EMAIL",
					template:new sap.m.Text({text:"{EMAIL}",tooltip:"{EMAIL}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Phone Number"}),
					width:"150px",
					sortProperty:"PHONENUM",
					filterProperty:"PHONENUM",
					template:new sap.m.Text({text:"{PHONENUM}",tooltip:"{PHONENUM}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Gender"}),
					width:"150px",
					sortProperty:"GENDER",
					filterProperty:"GENDER",
					template:new sap.m.Text({text:"{GENDER}",tooltip:"{GENDER}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Occupation"}),
					width:"150px",
					sortProperty:"OCCUPATION",
					filterProperty:"OCCUPATION",
					template:new sap.m.Text({text:"{OCCUPATION}",tooltip:"{OCCUPATION}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Date of Birth"}),
					width:"150px",
					sortProperty:"DATE_BIRTH",
					filterProperty:"DATE_BIRTH",
					template:new sap.m.Text({text:"{DATE_BIRTH}",tooltip:"{DATE_BIRTH}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Billing Address"}),
					width:"150px",
					sortProperty:"BILLING",
					filterProperty:"BILLING",
					template:new sap.m.Text({text:"{BILLING}",tooltip:"{BILLING}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Purchase History"}),
					width:"150px",
					sortProperty:"PURCHASE_HIS",
					filterProperty:"PURCHASE_HIS",
					template:new sap.m.Text({text:"{PURCHASE_HIS}",tooltip:"{PURCHASE_HIS}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Payment Method"}),
					width:"150px",
					sortProperty:"PAYMENT",
					filterProperty:"PAYMENT",
					template:new sap.m.Text({text:"{PAYMENT}",tooltip:"{PAYMENT}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Order Number"}),
					width:"150px",
					sortProperty:"ORDER",
					filterProperty:"ORDER",
					template:new sap.m.Text({text:"{ORDER}",tooltip:"{ORDER}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Company Name"}),
					width:"150px",
					sortProperty:"COMPANY",
					filterProperty:"COMPANY",
					template:new sap.m.Text({text:"{COMPANY}",tooltip:"{COMPANY}",maxLines:1}),
				}),
				
				
				// new sap.ui.table.Column({label:new sap.m.Text({text:"Created By"}),
				// 	width:"160px",
				// 	sortProperty:"created_by",
				// 	filterProperty:"created_by",
				// 	template:new sap.m.Text({text:"{created_by}",tooltip:"{created_by}",maxLines:1}),
				// }),
				// new sap.ui.table.Column({label:new sap.m.Text({text:"Creation Date"}),
				// 	width:"150px",
				// 	sortProperty:"created_at",
				// 	filterProperty:"created_at_desc",
				// 	template:new sap.m.Text({text:"{created_at_desc}",tooltip:"{created_at_desc}",maxLines:1}),
				// }),
				
			]
		});
		lv_Page.setCustomHeader(lv_header);
		lv_Page.addContent(lv_crumbs);
		lv_Page.addContent(lv_table);
		return lv_Page;
	}
	function createTestPage(){
		let page = new sap.m.Page("TEST_PAGE",{}).addStyleClass('sapUiSizeCompact');
		let crumbs = new sap.m.Breadcrumbs("TEST_CRUMBS",{
			currentLocationText: "Customer Info Listing",
			links: [
				new sap.m.Link({
					text:"Home",
					press:function(oEvt){
					// fn_click_breadcrumbs("HOME");
					}
				}),
				new sap.m.Link("TEST_LIST_CRUMBS",{
					text:"Customer Info Management",
					press:function(oEvt){
					//  fn_click_breadcrumbs("HOME");
					}
				}),
				
			]
		}).addStyleClass('breadcrumbs-padding');

		//page.addContent(crumbs);
		return page;


	}

</script>
